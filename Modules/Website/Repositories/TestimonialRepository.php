<?php

namespace Modules\Website\Repositories;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestimonialRepository extends Controller
{
    // Repository logic here

    // Testimonial Code

    public function TestimonialList()
    {

        return Controller::model('Testimonial', 'Website')::with('Coursedate', 'CourseCountryName')->orderby('id', 'DESC');
    }

    public static function TestimonialFilters($query, $request)
    {
        // dd($request->all());
        return $query
            ->when($request && $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by' && $request->custom_filter[0] != 'category', function ($query) use ($request) {
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

    public function TestimonialCreate($request, $view = null)
    {
        // dd($request->all());
        $data = null;
        $companyId = Controller::loginandcompany();
        $couserdata = Controller::model('Course', 'Course')::get();
        $CountryData = Controller::model('CourseCountry', 'Course')::get();

        if ($request?->id) {
            $id = decrypt($request?->id);
            $data = Controller::model('Testimonial', 'Website')::find($id);
            if (! $data) {
                return redirect()->back()->with('error', 'Location not found with the given ID.');
            }
            if ($view == 'view') {
                return view('website::testimonial.view', compact('data'));
            }
        }

        return view('website::testimonial.add', compact('data', 'couserdata', 'CountryData'));
    }

    public function TestimonialStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();

        $model = Controller::model('Testimonial', 'Website');
        $id = null;

        if ($request->testimonial_id) {
            $id = decrypt($request->testimonial_id);
            $entry = $model::find($id);
            $entry->updated_by = $cmp_and_user[0];

            $message = ' updated this Testimonial Center-'.$entry->name;
        } else {
            $entry = new $model;
            $entry->created_by = $cmp_and_user[0];

            $message = ' created this Testimonial Center-'.$request->name;
        }

        $entry->course_id = $request->course_id;
        $entry->country_id = $request->country_id;
        $entry->name = $request->name;
        $entry->comment = $request->comment;
        $entry->status = $request->status;
        $entry->job_position = $request->job_position;
        $entry->company_name = $request->company_name;
        $entry->course_overview_keywords = $request->course_overview_keywords;
        $entry->company_id = $cmp_and_user[1];

        $entry->save();

        return [
            'status' => true,
            'message' => $message,
            'data' => $entry,
        ];
    }

    // ///////////////////

    // Testimonial Code

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
            if (! $data) {
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
            if ($request->page_id) {
                $id = decrypt($request->page_id);
                $entry = $model::find($id);
                if (! $entry) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Pages not found with the given ID.'),
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Pages-'.$entry->title;
            } else {
                $entry = new $model;

                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Pages-'.$request->title;
            }
            $entry->title = $request->title;
            $entry->meta_title = $request->meta_title;
            $entry->meta_description = $request->meta_description;
            $entry->content = $request->content;
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
            catch_log($e, 'Pages');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Contact Code

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
            if (! $data) {
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
        // dd($request);
        $cmp_and_user = Controller::loginandcompany();
        try {
            Controller::db()::beginTransaction();
            $model = Controller::model('Contact', 'Website');
            $id = null;
            if ($request->contact_id) {
                $id = decrypt($request->contact_id);
                $entry = $model::find($id);
                if (! $entry) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Contact not found with the given ID.'),
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Contact-'.$entry->address;
            } else {
                $entry = new $model;

                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Contact-'.$request->address;
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
            catch_log($e, 'Contact');

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
            if (! $data) {
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
                if (! $entry) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Tags not found with the given ID.'),
                    ];
                }
                $entry->updated_by = $cmp_and_user[0];
                $message = ' updated this Tags-'.$entry->name;
            } else {
                $entry = new $model;
                $entry->created_by = $cmp_and_user[0];
                $message = ' created this Tags-'.$request->name;
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
            catch_log($e, 'Tags');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
