<?php

namespace Modules\Website\Repositories;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DOMDocument;

class WebsiteRepository extends Controller
{
    // Repository logic here

    //blogs Code

    public function blogsList()
    {

        return Controller::model('KnowledgeCenter', 'Website')::orderby('id', 'DESC');
    }
    public function getAllCount($request=null)
    {
        [$userId, $companyId] = Controller::loginandcompany();
        $query = Controller::model('KnowledgeCenter', 'Website')::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "Draft" THEN 1 ELSE 0 END) as draft_count,
            SUM(CASE WHEN resource_category = 239 THEN 1 ELSE 0 END) as Blog,
            SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN resource_category = 241 THEN 1 ELSE 0 END) as Article,
            SUM(CASE WHEN resource_category = 242 THEN 1 ELSE 0 END) as Info,
             SUM(CASE WHEN resource_category = 240 THEN 1 ELSE 0 END) as Webinar,
            SUM(CASE WHEN created_by = ? THEN 1 ELSE 0 END) as created_by_me_count
        ', [$userId])
        ->where('company_id', $companyId);
        $query = $this->blogsFilters($query, $request);
        return $query->first();
    }

    public static function blogsFilters($query, $request)
    {
       // dd($request->all());

        return $query
        ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' &&  $request->custom_filter[1]=="Article",  function ($query) use ($request) {
                $query->where('resource_category', 241);
            })
        ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' &&  $request->custom_filter[1]=="Webinar",  function ($query) use ($request) {
                $query->where('resource_category', 240);
            })
             ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' &&  $request->custom_filter[1]=="Info",  function ($query) use ($request) {
                $query->where('resource_category', 242);
            })
             ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' &&  $request->custom_filter[1]=="Blog",  function ($query) use ($request) {
                $query->where('resource_category', 239);
            })
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' &&  $request->custom_filter[1]!="Blog" &&  $request->custom_filter[1]!="Webinar" &&  $request->custom_filter[1]!="Article" &&  $request->custom_filter[1]!="Info",  function ($query) use ($request) {
                $query->where($request->custom_filter[0], $request->custom_filter[1]);
            })

            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by', function ($query) use ($request) {
                $item = $request->custom_filter[1];
                $query->whereHas('creator', function ($subQuery) use ($item) {
                    $subQuery->where('resourse_title', $item);
                });
            })
            ->when(
                $request && $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                function ($query) use ($request) {
                    $start_date = $request->custom_filter[1];
                    $end_date = $request->custom_filter[2];
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                }
            );


    }

    public function blogsCreate($id = null, $view = null)
    {
        $data = null;
        $companyId = Controller::loginandcompany();
        $blogcategories = masterParentData('Knowledge Center Category',  $companyId[1]);
        // $cousercategories = masterParentData('Category', $companyId[1]);
        $cousercategories = Controller ::model('CourseCategory' , 'Course')
                                        ::whereNull('main_category_id')
                                       ->where('status' , 'Active')
                                       ->get();

        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('KnowledgeCenter', 'Website')::find($id);
            if (!$data) {
                return redirect()->back()->with('error', 'Location not found with the given ID.');
            }
            if ($view == 'view') {
                return view('website::KnowledgeCenter.view', compact('data'));
            }
        }
        return view('website::KnowledgeCenter.add', compact('data','blogcategories','cousercategories'));
    }

    public function blogsStore($request)
    {
        // dd($request->all());
        $cmp_and_user = Controller::loginandcompany();
        try {
            Controller::db()::beginTransaction();
            $model = Controller::model('KnowledgeCenter', 'Website');
            $id = null;
            if ($request->knowledge_id) {
                $id = decrypt($request->knowledge_id);
                $entry = $model::find($id);
                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('Blog not found with the given ID.')
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Knowledge Center-' . $entry->title;
            } else {
                $entry = new $model();

                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Knowledge Center-' . $request->title;
            }
            $headings = $this->extractHeadingsFromContent($request->overview);
            $tableof_content = $this->generateTocFromHeadings($headings, $request->tableof_content);
            $contentWithIds = $this->addHeadingIds($request->overview, $headings);
            $entry->resource_category = $request->resourceCategory;
            $entry->course_category_id = $request->course_id;
            $entry->resource_auther = $request->resource_auther;
            $entry->short_description = $request->short_description;
            $entry->resource_title = $request->resource_title;
            $entry->overview = $contentWithIds;
            $entry->tableof_content = $tableof_content;
            $entry->meta_description = $request->meta_description;
            $entry->meta_keywords = $request->meta_keywords;
            $entry->page_title = $request->page_title;
            $entry->user_views = $request->user_views;
            //$entry->resource_category_id = $request->category_id;
            $entry->company_id = $cmp_and_user[1];
            $entry->status =$request->status;
            $entry->save();

            if ($request->hasFile('feature_image')) {

                $image = Controller::helper('CommonHelper')::folder_name($entry->id, 'KnowledgeCenter', 'KnowledgeCenter', $request->file('feature_image'), 15, 'images', 'Website');

                if (empty($image)) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }
                $entry->header_image = $image[1];
                $entry->save();
            }
            Controller::db()::commit();
            addToLog('Knowledge Center', $entry->id, $message);
            return [
                'status' => true,
                'message' => 'Knowledge Center saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            dd($e);
            Controller::db()::rollback();
            catch_log($e, "Knowledge Center");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function blogsDestroy($id)
    {
        try {
            $id = decrypt($id);
            $model = Controller::model('KnowledgeCenter', 'Website');
            $entry = $model::find($id);

            if (!$entry) {
                return [
                    'status' => false,
                    'message' => 'blog not found.',
                ];
            }

            $entry->delete();
            // $entry->deleted_at = now();
            // $entry->save();
            return [
                'status' => true,
                'message' => 'blogs deleted successfully.',
            ];
        } catch (\Exception $e) {
            catch_log($e, "Delete blogs");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function extractHeadingsFromContent($contentHtml)
    {
        $headings = [];

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div>' . $contentHtml . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $xpath = new \DOMXPath($dom);

        // Get all headings in document order
        $elements = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');

        foreach ($elements as $element) {

            $text = trim(strip_tags($element->textContent));

            if ($text) {

                $slug = Str::slug($text);

                $headings[] = [
                    'text' => $text,
                    'slug' => $slug,
                    'level' => (int) substr($element->nodeName, 1)
                ];
            }
        }

        return $headings;
    }


    private function generateTocFromHeadings($headings, $manualTocText = null)
    {
        if (empty($headings)) return '';

        $html = '<ul class="toc-list">';

        foreach ($headings as $heading) {
            $indent = ($heading['level'] - 1) * 20 . 'px';
            $html .= sprintf(
                '<li style="margin-left: %s"><a href="#%s">%s</a></li>',
                $indent,
                $heading['slug'],
                htmlspecialchars($heading['text'])
            );
        }

        $html .= '</ul>';
        return $html;
    }









    private function addHeadingIds($contentHtml, $headings)
    {
                if (empty($headings)) return $contentHtml;

                libxml_use_internal_errors(true);
                $dom = new \DOMDocument();
                $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $contentHtml . '</div>',
                            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                // Add IDs to ALL headings (not just TOC matched ones)
                $headingElements = $dom->getElementsByTagName('*');
                foreach ($headingElements as $element) {
                    if (preg_match('/h[1-6]/', $element->nodeName)) {
                        $text = trim(strip_tags($element->textContent));
                        if ($text) {
                            $slug = Str::slug($text);
                            $element->setAttribute('id', $slug);
                        }
                    }
                }

                $body = $dom->getElementsByTagName('div')->item(0);
                return preg_replace('/^<div[^>]*>(.*)<\/div>$/s', '$1', $dom->saveHTML($body));
    }

    /////////////////////

    //blogs Code

    public function pageList()
    {

        return Controller::model('Pages', 'Website')::orderby('id', 'DESC');
    }

    public static function pageListFilters($query, $request)
    {

        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by', function ($query) use ($request) {
                $query->where($request->custom_filter[0], $request->custom_filter[1]);
            })
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by', function ($query) use ($request) {
                $item = $request->custom_filter[1];
                $query->whereHas('creator', function ($subQuery) use ($item) {
                    $subQuery->where('name', $item);
                });
            })
            ->when(
                $request && $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                function ($query) use ($request) {
                    $start_date = $request->custom_filter[1];
                    $end_date = $request->custom_filter[2];
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                }
            );
    }

    public function pageCreate($id = null, $view = null)
    {
        $data = null;
        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('Pages', 'Website')::find($id);
            if (!$data) {
                return redirect()->back()->with('error', 'Location not found with the given ID.');
            }
            if ($view == 'view') {
                return view('website::pages.view', compact('data'));
            }
        }
        return view('website::pages.add', compact('data'));
    }

    public function pageStore($request)
    {

        $cmp_and_user = Controller::loginandcompany();
        try {
            Controller::db()::beginTransaction();
            $model = Controller::model('Pages', 'Website');
            $id = null;

            $restrictedStatus = ['Disclaimer-footer', 'All-Course'];
            if (in_array($request->status, $restrictedStatus))
                 {
                    $existingQuery = $model::where('status', $request->status)
                                          ->where('company_id', $cmp_and_user[1]);

                  if ($request->page_id)
                    {
                        $existingQuery->where('id', '!=', decrypt($request->page_id));
                    }
                    $exists = $existingQuery->exists();
                     if ($exists)
                        {
                        Controller::db()::rollback();
                        return [ 'status' => false,
                                    'message' => $request->status . ' page already exists. Only one record is allowed.' ];
                        }
                    }

            if ($request->page_id) {
                $id = decrypt($request->page_id);
                $entry = $model::find($id);
                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('Pages not found with the given ID.')
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Pages-' . $entry->title;
            } else {
                $entry = new $model();

                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Pages-' . $request->title;
            }
            $entry->title = $request->title;
            $entry->meta_title = $request->meta_title;
            $entry->meta_description = $request->meta_description;
            $entry->content = $request->content;
            $entry->advantages1 = $request->advantages1;
            $entry->advantages2 = $request->advantages2;
            $entry->advantages3 = $request->advantages3;

            $entry->company_id = $cmp_and_user[1];
            $entry->status = $request->status;
            $entry->save();

            if ($request->hasFile('page_image')) {

                $image = Controller::helper('CommonHelper')::folder_name($entry->id, 'Pages', 'Pages', $request->file('page_image'), 15, 'images', 'Website');

                if (empty($image)) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }
                $entry->pages_img = $image[1];
                $entry->save();
            }
            Controller::db()::commit();
            addToLog('Pages', $entry->id, $message);
            return [
                'status' => true,
                'message' => 'Pages saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            //   dd($e);
            Controller::db()::rollback();
            catch_log($e, "Pages");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }



    //Contact Code

    public function contactList()
    {

        return Controller::model('Contact', 'Website')::orderby('id', 'DESC');
    }

    public static function contactListFilters($query, $request)
    {

        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by', function ($query) use ($request) {
                $query->where($request->custom_filter[0], $request->custom_filter[1]);
            })
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by', function ($query) use ($request) {
                $item = $request->custom_filter[1];
                $query->whereHas('creator', function ($subQuery) use ($item) {
                    $subQuery->where('name', $item);
                });
            })
            ->when(
                $request && $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                function ($query) use ($request) {
                    $start_date = $request->custom_filter[1];
                    $end_date = $request->custom_filter[2];
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                }
            );
    }

    public function contactCreate($id = null, $view = null)
    {
        $data = null;
        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('Contact', 'Website')::find($id);
            if (!$data) {
                return redirect()->back()->with('error', 'Location not found with the given ID.');
            }
            if ($view == 'view') {
                return view('website::contact.view', compact('data'));
            }
        }
        return view('website::contact.add', compact('data'));
    }

    public function contactStore($request)
    {
        //dd($request);
        $cmp_and_user = Controller::loginandcompany();
        try {
            Controller::db()::beginTransaction();
            $model = Controller::model('Contact', 'Website');
            $id = null;
            if ($request->contact_id) {
                $id = decrypt($request->contact_id);
                $entry = $model::find($id);
                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('Contact not found with the given ID.')
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Contact-' . $entry->address;
            } else {
                $entry = new $model();

                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Contact-' . $request->address;
            }
            $entry->address = $request->address;
            $entry->phone = $request->phone;
            $entry->meta_description = $request->meta_description;
            $entry->email = $request->email;
            $entry->Hours = $request->timing;
            $entry->facebook = $request->facebook;
            $entry->youtube = $request->youtube;

            $entry->x = $request->x;
            $entry->instagram = $request->instagram;
            $entry->linkedin = $request->linkedin;
            $entry->map = $request->map;

            $entry->company_id = $cmp_and_user[1];
            $entry->status = $request->status;
            $entry->save();

            if ($request->hasFile('contact_image')) {

                $image = Controller::helper('CommonHelper')::folder_name($entry->id, 'Contact', 'Contact', $request->file('contact_image'), 15, 'images', 'Website');

                if (empty($image)) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }
                $entry->contact_img = $image[1];
                $entry->save();
            }
            Controller::db()::commit();
            addToLog('Contact', $entry->id, $message);
            return [
                'status' => true,
                'message' => 'Contact saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            //   dd($e);
            Controller::db()::rollback();
            catch_log($e, "Contact");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    // tabs code ---
     // For direct column filters except created_at/created_by

    public function tagsList()
    {
        return Controller::model('Tags', 'Website')::orderby('id', 'DESC');
    }
    public static function tagsFilters($query, $request)
    {
        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by', function ($query) use ($request) {
                $query->where($request->custom_filter[0], $request->custom_filter[1]);
            })
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by', function ($query) use ($request) {
                $item = $request->custom_filter[1];
                $query->whereHas('creator', function ($subQuery) use ($item) {
                    $subQuery->where('name', $item);
                });
            })
            ->when(
                $request && $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                function ($query) use ($request) {
                    $start_date = $request->custom_filter[1];
                    $end_date = $request->custom_filter[2];
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                }
            );
    }
    public function tagsCreate($id = null, $view = null)
    {
        $data = null;
        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('Tags', 'Website')::find($id);
            if (!$data) {
                return redirect()->back()->with('error', 'Tags not found with the given ID.');
            }
            if ($view == 'view') {
                return view('website::tags.view', compact('data'));
            }
        }
        return view('website::tags.add', compact('data'));
    }

    public function tagsStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        try {
            Controller::db()::beginTransaction();
            $model = Controller::model('Tags', 'Website');
            $id = null;
            if ($request->tags_id) {
                $id = decrypt($request->tags_id);
                $entry = $model::find($id);
                if (!$entry) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('Tags not found with the given ID.')
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Tags-' . $entry->name;
            } else {
                $entry = new $model();
                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Tags-' . $request->name;
            }
            $entry->name = $request->name;
            $entry->description = $request->description;
            $entry->company_id = $cmp_and_user[1];
            $entry->save();
            Controller::db()::commit();
            addToLog('Tags', $entry->id, $message);
            return [
                'status' => true,
                'message' => 'Tags saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Tags");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // ==================== slider ================

    public function sliderList()
    {
        return Controller::model('Slider', 'Website')::orderby('id', 'DESC');
    }


    public static function sliderFilters($query, $request)
    {
        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by', function ($query) use ($request) {
                $query->where($request->custom_filter[0], $request->custom_filter[1]);
            })
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by', function ($query) use ($request) {
                $item = $request->custom_filter[1];
                $query->whereHas('creator', function ($subQuery) use ($item) {
                    $subQuery->where('name', $item);
                });
            })
            ->when(
                $request && $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                function ($query) use ($request) {
                    $start_date = $request->custom_filter[1];
                    $end_date = $request->custom_filter[2];
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                }
            );
    }

    public function sliderCreate($id = null, $view = null)
    {
        $data = null;
        $existingImages = [];
        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('Slider', 'Website')::find($id);
            if (!$data) {
                return redirect()->back()->with('error', 'Tags not found with the given ID.');
            }
            if ($view == 'view') {
                return view('website::slider.view', compact('data'));
            }
            $existingImages = json_decode($data->slider_images, true) ?? [];
        }



        return view('website::slider.add', compact('data' , 'existingImages'));
    }

    public function sliderStore($id = null, $request)
    {
        $model = Controller::model('Slider', 'Website');
        $cmp_and_user = Controller::loginandcompany();
        try {
            if ($id) {
                $entry = $model::find($id);
                if (!$entry) {
                    return [
                        'status' => false,
                        'message' => __('website not found with the given ID.'),
                    ];
                }
            } else {
                $entry = new $model();
            }

            $imagePath = $entry->section_image;
            if (!empty($request['sectionImage'])) {
                if ($entry->section_image && file_exists(public_path($entry->section_image))) {
                    unlink(public_path($entry->section_image));
                }
                $image = $request['sectionImage'];
                $filename = $image->getClientOriginalName();
                $destination = 'assets/images/slider';
                $image->move(public_path($destination), $filename);
                $imagePath = $destination . '/' . $filename;
            }


            $existingSliderImages = $entry->slider_images ? json_decode($entry->slider_images, true) : [];
            if (!is_array($existingSliderImages)) {
                $existingSliderImages = [];
            }
            $newSliderImages = [];
            if (!empty($request["slider_images"])) {
                foreach ($request["slider_images"] as $sliderImage) {
                    if ($sliderImage->isValid()) {
                        $filename = $sliderImage->getClientOriginalName();
                        $destination = 'assets/images/slider';
                        $sliderImage->move(public_path($destination), $filename);
                        $newSliderImages[] = $destination . '/' . $filename;
                    }
                }
            }
            $sliderImagePaths = array_merge($existingSliderImages, $newSliderImages);


            if (!empty($request['deleted_images'])) {
                $deletedImages = json_decode($request['deleted_images'], true);
                $sliderImagePaths = array_diff($sliderImagePaths, $deletedImages);
            }


            $entry->sub_heading = $request["sub_heading"];
            $entry->heading = $request["heading"];
            $entry->main_content = $request["mainContent"];
            $entry->section_image = $imagePath;
            $entry->label_one = $request["label_one"];
            $entry->label_one_counter = $request["label_one_counter"];
            $entry->label_two = $request["label_two"];
            $entry->label_two_counter = $request["label_two_counter"];
            $entry->slider_images = json_encode($sliderImagePaths, JSON_UNESCAPED_SLASHES);
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();

            $message = $id ? 'Updated Slider - ' : 'Created Slider - ';
            addToLog('Tags', $entry->id, $message);

            return [
                'status' => true,
                'message' => 'Slider saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Slider Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }




}
