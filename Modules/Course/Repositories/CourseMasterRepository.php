<?php

namespace Modules\Course\Repositories;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseMasterRepository extends Controller
{
    public function CourseList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('Course', 'Course')::with('course_category')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public function getAllCount($request = null)
    {
        [$userId, $companyId] = Controller::loginandcompany();
        $query = Controller::model('Course', 'Course')::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "Draft" THEN 1 ELSE 0 END) as draft_count,
            SUM(CASE WHEN status = "In review" THEN 1 ELSE 0 END) as inreview_count,
            SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected_count,
            SUM(CASE WHEN created_by = ? THEN 1 ELSE 0 END) as created_by_me_count
        ', [$userId])
            ->where('company_id', $companyId);
        $query = $this->CourseFilters($query, $request);

        return $query->first();
    }

    public static function CourseFilters($query, $request)
    {
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

    public function CourseCreate($id = null, $view = null)
    {
        try {
            $data = null;
            if ($id) {

                $id = decrypt($id);
                $data = Controller::model('Course', 'Course')::find($id);
                if (! $data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }
                if ($view == 'view') {
                    $companyId = Controller::loginandcompany();
                    $categories = masterParentData('Category', $companyId[1]);

                    return view('Course::Master.Course.view', compact('data', 'categories'));
                }
            }
            $companyId = Controller::loginandcompany();
            $categories = Controller::model('CourseCategory', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
            $counrty = Controller::model('CourseCountry', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();

            return view('Course::Master.Course.add', compact('categories', 'data','counrty'));
        } catch (\Exception $e) {

            Controller::db()::rollback();
            catch_log($e, 'Course Course');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function CourseStore($request)
    {
       // dd($request->all());

        $cmp_and_user = Controller::loginandcompany();

        try {
            Controller::db()::beginTransaction();

            $model = Controller::model('Course', 'Course');
            $id = null;
            $isUpdate = false;

            // 🔹 If course_id exists → Update
            if (! empty($request->course_id)) {
                $id = decrypt($request->course_id);
                $entry = $model::find($id);
                $entry->updated_by = $cmp_and_user[0];
                $isUpdate = true;
                $message = 'Updated Course - '.$entry->code;
            }
            // 🔹 Else → Create new record
            else {
                $entry = new $model;
                $entry->created_by = $cmp_and_user[0];
                $message = 'Created new Course - '.$request->course_title;
            }

            // 🔸 Assign values
            $entry->course_title = $request->course_title;
            $entry->course_short_name = $request->course_short_name;
            $entry->course_category_id = $request->course_category_id;
            $entry->banner_bg_color = $request->banner_bg_color;
            $entry->is_demand_course = $request->is_demand_course;
            $entry->overview = $request->overview;
            $entry->training_options = $request->training_options;
            $entry->course_curriculum = $request->course_curriculum;
            $entry->course_details = $request->course_details;
            $entry->meta_description = $request->meta_description;
            $entry->meta_keywords = $request->meta_keywords;
            $entry->page_title = $request->page_title;
            $entry->url_title = $request->url_title;
            $entry->short_description = $request->short_description;
            $entry->updated_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();
            $descriptionTemplate = $request->short_description;

           foreach ($request->country_id as $country_id) {

                // Get country object
                $country = Controller::model('CourseCountry', 'Course')::find($country_id);

                // =========================================================
                // 🔹 1. SAVE COUNTRY LEVEL PAGE
                // =========================================================
                $pageModel = Controller::model('DynamicCoursePages', 'Course');

                $page = $pageModel::firstOrCreate([
                    'course_id'  => $entry->id,
                    'country_id' => $country_id,
                    'state_id'   => null,
                ], [
                    'is_ready'   => 'yes',
                    'created_by'=> $cmp_and_user[0],
                    'company_id'=> $cmp_and_user[1],
                ]);

                // =========================================================
                // 🔹 2. SAVE COUNTRY LEVEL SEO
                // =========================================================

               $bulletPointsModel = Controller::model('CourseHighlights', 'Course');
                $bulletPoints = new $bulletPointsModel;
                    $bulletPoints->course_id = $entry->id; // recommended if exists
                    $bulletPoints->country_id = $country->id ?? null; // optional
                    $bulletPoints->course_description = $bulletPoints->course_description = str_replace(
                                                            '<Dynamic>',
                                                            $country->name,
                                                            $descriptionTemplate
                                                        );
                    $bulletPoints->created_by = $cmp_and_user[0] ?? null;
                    $bulletPoints->company_id = $cmp_and_user[1] ?? null;
                    $bulletPoints->save();

                $headlineModel = Controller::model('DynamicCourseHeadline', 'Course');

                $headline = $headlineModel::firstOrCreate([
                    'course_id'  => $entry->id,
                    'country_id' => $country_id,
                    'state_id'   => null,
                ]);

                $headline->page_title          = $request->page_title . ' in ' . $country->name;
                $headline->meta_title          = $request->page_title . ' in ' . $country->name;
                $headline->meta_description    = $request->short_description . ' in ' . $country->name;
                $headline->hone_tag            = $request->course_short_name;
                $headline->imageAltText        = $request->page_title . ' in ' . $country->name;
                $headline->metaDescription     = $request->meta_description . ' in ' . $country->name;
                $headline->imageAltDescription = $request->meta_description . ' in ' . $country->name;
                $headline->created_by          = $cmp_and_user[0];
                $headline->company_id          = $cmp_and_user[1];
                $headline->save();
               $states = Controller::model('State', 'Course')
                ::where('country_id', $country_id)
                ->get();

            foreach ($states as $state) {

                // =========================================================
                // 🔹 3. SAVE STATE LEVEL PAGE
                // =========================================================
                $page = $pageModel::firstOrCreate([
                    'course_id'  => $entry->id,
                    'country_id' => $country_id,
                    'state_id'   => $state->id,
                ], [
                    'is_ready'   => 'yes',
                    'created_by'=> $cmp_and_user[0],
                    'company_id'=> $cmp_and_user[1],
                ]);

                // =========================================================
                // 🔹 4. SAVE STATE LEVEL SEO
                // =========================================================
                $headline = $headlineModel::firstOrCreate([
                    'course_id'  => $entry->id,
                    'country_id' => $country_id,
                    'state_id'   => $state->id,
                ]);

                $headline->page_title          = $request->page_title . ' in ' . $state->name;
                $headline->meta_title          = $request->page_title . ' in ' . $state->name;
                $headline->meta_description    = $request->short_description . ' in ' . $state->name;
                $headline->hone_tag            = $request->course_short_name;
                $headline->imageAltText        = $request->page_title . ' in ' . $state->name;
                $headline->metaDescription     = $request->meta_description . ' in ' . $state->name;
                $headline->imageAltDescription = $request->meta_description . ' in ' . $state->name;
                $headline->created_by          = $cmp_and_user[0];
                $headline->company_id          = $cmp_and_user[1];
                $headline->save();


                    $bulletPointsModel = Controller::model('CourseHighlights', 'Course');
                    $bulletPoints = new $bulletPointsModel;
                    $bulletPoints->course_id = $entry->id; // recommended if exists
                    $bulletPoints->country_id = $country->id ?? null; // optional
                    $bulletPoints->state_id   = $state->id;
                    $bulletPoints->course_description = $bulletPoints->course_description = str_replace(
                                                            '<Dynamic>',
                                                            $state->name,
                                                            $descriptionTemplate
                                                        );
                    $bulletPoints->created_by = $cmp_and_user[0] ?? null;
                    $bulletPoints->company_id = $cmp_and_user[1] ?? null;
                    $bulletPoints->save();
            }
        }





           // dd($data);


            // 🔸 Handle feature image upload (optional)
            if ($request->hasFile('feature_image')) {
                $image = Controller::helper('CommonHelper')::folder_name(
                    $entry->id,
                    'Course',
                    'Course',
                    $request->file('feature_image'),
                    15,
                    'images',
                    'Course'
                );

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

            // 🔸 Add log entry
            addToLog('CourseCourse', $entry->id, $message);

            Controller::db()::commit();

            return [
                'status' => true,
                'message' => $isUpdate
                    ? __('Course updated successfully.')
                    : __('Course created successfully.'),
                'data' => $entry,
            ];

        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, 'Course Course');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }

    }



    private function generateEAN13($companyId, $categoryId, $unitId, $sequence)
    {
        // {!! DNS1D::getBarcodeHTML($Course->sku_code, 'EAN13') !!}
        // <small>{{ $Course->sku_code }}</small><br>

        $company = str_pad($companyId, 2, '0', STR_PAD_LEFT);
        $category = str_pad($categoryId ?? 0, 2, '0', STR_PAD_LEFT);
        $unit = str_pad($unitId ?? 0, 2, '0', STR_PAD_LEFT);
        $sequence = str_pad($sequence, 5, '0', STR_PAD_LEFT);

        $number = $company.$category.$unit.$sequence;
        $number = str_pad($number, 12, '0', STR_PAD_LEFT);
        $checksum = 0;
        foreach (str_split(strrev($number)) as $i => $digit) {
            $checksum += (intval($digit)) * ($i % 2 === 0 ? 3 : 1);
        }
        $checksum = (10 - ($checksum % 10)) % 10;

        return $number.$checksum;
    }

    public function get_filtered_StateName($request)
    {
        $query = Controller::model('State', 'Course')::where('country_id', $request->parent_id)
            ->orderBy('id', 'DESC');

        return $query->get();
    }

    public function get_filtered_Course($request)
    {
        $companyId = Controller::loginandcompany();
        $filters = $request->custom_filter ?? [];

        $query = Controller::model('CourseCourse', 'Course')::where('company_id', $companyId[1])
            ->orderBy('id', 'DESC');

        $query->when(isset($filters['name']), function ($q) use ($filters) {
            $q->where('name', 'like', '%'.$filters['name'].'%');
        });

        $query->when(isset($filters['category_id']), function ($q) use ($filters) {
            $q->where('category_id', $filters['category_id']);
        });

        $query->when(isset($filters['subcategory_id']), function ($q) use ($filters) {
            $q->where('subcategory_id', $filters['subcategory_id']);
        });

        return $query->get();
    }

    // Corse Country Code

    public function countryList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('CourseCountry', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function CountryFilters($query, $request)
    {
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

        public function countryCreate($id = null, $view = null)
    {
        $data = null;
        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('CourseCountry', 'Course')::find($id);
            // dd($data);
            if (! $data) {
                return redirect()->back()->with('error', 'Location not found with the given ID.');
            }
          }
        $companyId = Controller::loginandcompany();
        $countries = Controller::model('CourseCountry', 'Course')::get();
        $location_type = masterParentData('Location-Type');

        return view('Course::Master.Country.add', compact('data', 'countries', 'location_type'));
    }


    public function updateCountry(Request $request): array
    {
        try {
            $encryptedId = $request->input('location_id');
            if (!$encryptedId) {
                return ['status' => false, 'message' => 'Location ID required'];
            }

            $id = decrypt($encryptedId);
            $id = (int) $id;

            // dd($id);

            // ✅ Get clean data
            $data = $request->except(['_token', 'location_id']);

            // ✅ Direct DB - BYPASS all repos/services
            $country = DB::table('tbl_course_country')->where('id', $id)->first();
            if (!$country) {
                return ['status' => false, 'message' => 'Country not found'];
            }

            // ✅ Update ONLY non-empty fields (preserve old DB values)
            $updateData = [];
            $allowedFields = [
                'name', 'iso3', 'numeric_code', 'iso2', 'phonecode', 'capital',
                'currency', 'value_in_dollar', 'currency_name', 'currency_symbol',
                'tld', 'native', 'region', 'subregion', 'latitude', 'longitude',
                'wikiDataId', 'status'
            ];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data) &&
                    $data[$field] !== null &&
                    $data[$field] !== '' &&
                    $data[$field] !== []) {
                    $updateData[$field] = $data[$field];
                }
            }

            // ✅ Timezones (JSON)
            if (isset($data['timezones']) && is_array($data['timezones'])) {
                $updateData['timezones'] = json_encode($data['timezones']);
            }

            // ✅ Auto-generate fields
            if (!empty($updateData['name'] ?? '')) {
                $updateData['url'] = $updateData['name'];
            }
            if (!empty($updateData['phonecode'] ?? '')) {
                $updateData['phone_number'] = $updateData['phonecode'];
            }

            // ✅ Update if changes exist
            if (!empty($updateData)) {
                $updateData['updated_at'] = now();
                DB::table('tbl_course_country')
                    ->where('id', $id)
                    ->update($updateData);
            }

            return [
                'status' => true,
                'message' => 'Course country updated successfully.',
                'data' => DB::table('tbl_course_country')->where('id', $id)->first()
            ];

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return ['status' => false, 'message' => 'Invalid location ID'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Update failed'];
        }
    }


    // ============== location ==========

    public function locationCreate($id = null, $view = null)
    {
        $data = null;
        if ($id) {
            $id = decrypt($id);
            $data = Controller::model('CourseLocation', 'Course')::with('locationType')->find($id);
            if (! $data) {
                return redirect()->back()->with('error', 'Location not found with the given ID.');
            }
            if ($view == 'view') {
                return view('Course::Master.Location.view', compact('data'));
            }
        }
        $companyId = Controller::loginandcompany();
        $countries = Controller::model('Country', 'DynamicMaster')::ActiveOnly()->get();
        $location_type = masterParentData('Location-Type');

        return view('Course::Master.country.add', compact('data', 'countries', 'location_type'));
    }

    public function locationStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        // try {
        Controller::db()::beginTransaction();
        $model = Controller::model('CourseCourse', 'Course');
        $id = null;
        if ($request->Course_id) {
            $id = decrypt($request->Course_id);
            $entry = $model::find($id);
            if (! $entry) {
                Controller::db()::rollback();

                return [
                    'status' => false,
                    'message' => __('Course not found with the given ID.'),
                ];
            }
            $entry->updated_by = $cmp_and_user[0];
            $message = ' updated this Course-'.$entry->code;
        } else {

            $max_id = Controller::helper('NumberSequenceHelpers')::apply_no_sequence('Course Course', $cmp_and_user[1]);

            if ($max_id == 0 || $max_id == 1) {
                Controller::db()::rollback();

                return [
                    'status' => false,
                    'message' => 'Something went wrong. Contact to System Administrator!',
                ];
            }
            $entry = new $model;
            $entry->code = $max_id;
            $entry->sku_code = $this->generateEAN13($cmp_and_user[1], $request->category_id, $request->unit_id, $max_id);
            $entry->created_by = $cmp_and_user[0];
            $message = ' created this Course-'.$entry->code;
        }
        $entry->category_name = $request->category_name;
        $entry->description = $request->page_link;
        $entry->parent_category_id = $request->parent_category_id;
        $entry->short_description = $request->short_description;
        $entry->scholaracad_advantage = $request->brand_name;
        $entry->Option1 = $request->Option1;
        $entry->Option2 = $request->Option2;
        $entry->Option3 = $request->Option3;
        $entry->Option4 = $request->Option4;
        $entry->Option5 = $request->Option5;
        $entry->Option6 = $request->Option6;
        $entry->Option7 = $request->Option7;
        $entry->Option8 = $request->Option8;
        $entry->Course_image = $request->Course_image;
        $entry->knowledge_images = $request->knowledge_images;

        $entry->Option3 = $request->status;
        $entry->knowledge_partner = $request->knowledge_partner;
        $entry->knowledge_button = $request->knowledge_button;
        $entry->knowledge_link = $request->knowledge_link;
        $entry->knowledge_description = $request->address_line1;

        $entry->save();

        if ($request->hasFile('feature_image')) {
            $image = Controller::helper('CommonHelper')::folder_name($entry->id, 'CourseCourse', 'Course', $request->file('feature_image'), 15, 'images', 'Course');
            if (empty($image)) {
                Controller::db()::rollback();

                return [
                    'status' => false,
                    'message' => __('common.folder-name-not-generating'),
                ];
            }
            $entry->Course_image = $image[1];
            $entry->save();
        }
        if ($request->hasFile('knowledge_images')) {
            $knowledge_images = Controller::helper('CommonHelper')::folder_name($entry->id, 'CourseCourse', 'Course', $request->file('knowledge_images'), 15, 'images', 'Course');
            if (empty($knowledge_images)) {
                Controller::db()::rollback();
                return [
                    'status' => false,
                    'message' => __('Course.folder-name-not-generating of Knowledge images'),
                ];
            }
            $entry->knowledge_images = $knowledge_images[1];
            $entry->save();
        }
        Controller::helper('NumberSequenceHelpers')::update_no_sequence('Course Course', $cmp_and_user[1]);
        Controller::db()::commit();
        addToLog('CourseCourse', $entry->id, $message);

        return [
            'status' => true,
            'message' => 'Course Course saved successfully.',
            'data' => $entry,
        ];
    }

    // / Course State code by Lal Singh
    // Corse Country Code

    public function stateList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('State', 'Course')::with('country')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function stateFilters($query, $request)
    {
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
    public function stateStore( $id = null , $data)
    {
        $model = Controller::model('State', 'Course');
        $cmp_and_user = Controller::loginandcompany();
        //  dd($data);
        try {
            if ($id) {
                $entry = $model::find($id);
                if (!$entry) {
                    return [
                        'status' => false,
                        'message' => __('Course not found with the given ID.'),
                    ];
                }
            } else {
                $entry = new $model();
            }

            $country = Controller::model('CourseCountry', 'Course')::find($data['country_id']);
        if (!$country) {
            return [
                'status' => false,
                'message' => __('Country not found with the given ID.'),
            ];
        }
        $countryCode = $country->iso3;
        $iso2 = $country->iso2;

        //  dd($countryCode);
           $entry->country_code = $countryCode;
            $entry->name = $data['name'];
            $entry->url = Str::slug($data['name']);
            $entry->country_id = $data['country_id'];
            $entry->iso2 = $iso2;
            $entry->latitude = $data['gps_latitude'];
            $entry->longitude = $data['gps_longitude'];
            $entry->type = $data['statetype'] ?? null;
            $entry->status = $data['status'] ?? null;
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();


            $message = $id
                ? 'Updated Course certificate for Course - ' . $data['country_id']
                : 'Created Course certificate for Course - ' . $data['country_id'];

            addToLog('State', $data['country_id'], $message);

            return [
                'status' => true,
                'message' => ' State  saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course Certificate Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

        public function stateCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect();

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('State', 'Course')::with('country')->find($id);
                // dd($data);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                if ($view == 'view') {
                    $companyId = Controller::loginandcompany();
                    $states = masterParentData('state', $companyId[1]);

                    return view('Course::Master.State.view', compact('data', 'states'));
                }

                $multidat = Controller::model('State', 'Course')
                    ::where('country_id', $data->country_id)
                    ->get();

            }
            $multidat = Controller::model('State', 'Course')
                ::where('country_id', $data?->country_id)
                ->get();
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            return view('Course::Master.State.add', compact('courses', 'data', 'multidat' , 'countries' ));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course Certificate");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Corse Country Code

    public function cityList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('City', 'Course')::with('country', 'state')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function cityFilters($query, $request)
    {
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


    public function CityCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect();
            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('City', 'Course')::find($id);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                 if ($view == 'view') {
                    $companyId = Controller::loginandcompany();
                    $city = masterParentData('City', $companyId[1]);

                    return view('Course::Master.City.view', compact('data', 'city'));
                }

                $multidat = Controller::model('City', 'Course')
                    ::where('country_id', $data->country_id)
                    ->get();

            }
            $multidat = Controller::model('City', 'Course')
                ::where('country_id', $data?->country_id)
                ->get();
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $states = Controller::model('State', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            // dd($states);
            return view('Course::Master.City.add', compact('courses', 'data', 'multidat' , 'countries' ,'states'));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course Certificate");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

     public function cityStore( $id = null , $data)
    {

        $model = Controller::model('City', 'Course');
        $cmp_and_user = Controller::loginandcompany();
        try {
            if ($id) {
                $entry = $model::find($id);
                if (!$entry) {
                    return [
                        'status' => false,
                        'message' => __('Course not found with the given ID.'),
                    ];
                }
            } else {
                $entry = new $model();
            }

            $country = Controller::model('CourseCountry', 'Course')::find($data['country_id']);
        if (!$country) {
            return [
                'status' => false,
                'message' => __('Country not found with the given ID.'),
            ];
        }
        $countryCode = $country->iso3;
        $iso2 = $country->iso2;

        //  dd($countryCode);
           $entry->country_code = $countryCode;
            $entry->name = $data['name'];
            $entry->url = Str::slug($data['name']);
            $entry->country_id = $data['country_id'];
            $entry->state_id = $data['state_id'];
            $entry->latitude = $data['gps_latitude'];
            $entry->longitude = $data['gps_longitude'];
            $entry->status = $data['status'] ?? null;
            $entry->created_by = $cmp_and_user[0];
            $entry->company_id = $cmp_and_user[1];
            $entry->save();


            $message = $id
                ? 'Updated Course certificate for Course - ' . $data['country_id']
                : 'Created Course certificate for Course - ' . $data['country_id'];

            addToLog('City', $data['country_id'], $message);

            return [
                'status' => true,
                'message' => ' City saved successfully.',
                'data' => $entry,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "City Store");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Corse Country Code

    public function coursecategoryList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('CourseCategory', 'Course')::with('parent')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function coursecategoryFilters($query, $request)
    {
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

    public function CourseCategoryCreate($request)
    {
        try {
            $data = null;

            if (!empty($request->id)) {
                $id = decrypt($request->id);
                $data = Controller::model('CourseCategory', 'Course')::find($id);
            }

            return view('Course::Master.CourseCategory.add', compact('data'));

        } catch (\Exception $e) {
            catch_log($e, 'Course Course');

            return back()->with('error', $e->getMessage());
        }
    }


    public function cousecategoryStore($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        Controller::db()::beginTransaction();

        try {

            $model = Controller::model('CourseCategory', 'Course');

            // UPDATE
            if ($request->course_category_id) {

                $id = decrypt($request->course_category_id);
                $entry = $model::find($id);

                if (! $entry) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Course not found with the given ID.'),
                    ];
                }

                $entry->updated_by = $cmp_and_user[0];
                $message = "Updated course category - {$entry->category_name}";

            } else {

                // CREATE
                $entry = new $model;
                $entry->created_by = $cmp_and_user[0];
                $message = "Created course category - {$request->category_name}";
            }

            // FILL DATA
            $entry->category_name = $request->category_name;
            $entry->page_link = $request->page_link;
            $entry->main_category_id = $request->parent_category_id;
            $entry->short_description = $request->short_description;
            $entry->scholaracad_advantage = $request->scholaracad_advantage;

            $entry->Option1 = $request->Option1;
            $entry->Option2 = $request->Option2;
            $entry->Option3 = $request->Option3;
            $entry->Option4 = $request->Option4;
            $entry->Option5 = $request->Option5;
            $entry->Option6 = $request->Option6;
            $entry->Option7 = $request->Option7;
            $entry->Option8 = $request->Option8;

            $entry->status = $request->status;
            $entry->knowledge_partner = $request->knowledge_partner;
            $entry->knowledge_button = $request->knowledge_button;
            $entry->knowledge_link = $request->knowledge_link;
            $entry->knowledge_description = $request->knowledge_description;

            $entry->company_id = $cmp_and_user[1];

            $entry->save();

            // FEATURE IMAGE UPLOAD
            if ($request->hasFile('feature_image')) {

                $image = Controller::helper('CommonHelper')::folder_name(
                    $entry->id,
                    'CourseCategory',
                    'Course',
                    $request->file('feature_image'),
                    15,
                    'images',
                    'Course'
                );

                if (empty($image)) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }

                $entry->mainImages = $image[1];
                $entry->save();
            }

            // KNOWLEDGE IMAGES
            if ($request->hasFile('knowledge_images')) {

                $knowledge_images = Controller::helper('CommonHelper')::folder_name(
                    $entry->id,
                    'CourseCategory',      // FIXED
                    'Course',
                    $request->file('knowledge_images'),
                    15,
                    'image_document',
                    'Course'
                );

                if (empty($knowledge_images)) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Course.folder-name-not-generating of brochure'),
                    ];
                }

                $entry->knowledge_images = $knowledge_images[1];
                $entry->save();
            }

            Controller::db()::commit();

            return [
                'status' => true,
                'message' => $message,
                'data' => $entry,
            ];

        } catch (\Exception $e) {

            Controller::db()::rollback();
            throw $e;
        }
    }
public function CourseChildCategoryCreate($request)
    {
        try {
            $data = null;
            $category = Controller::model('CourseCategory', 'Course')::whereNull('main_category_id')->get();
            return view('Course::Master.CourseCategory.addChildCategory', compact('category'));

        } catch (\Exception $e) {
            catch_log($e, 'Course Course');
            return back()->with('error', $e->getMessage());
        }
    }
public function couseChildcategoryStore($request)
    {
         $cmp_and_user = Controller::loginandcompany();
        Controller::db()::beginTransaction();

        try {

            $model = Controller::model('CourseCategory', 'Course');

            // UPDATE
            if ($request->course_child_category_id) {

                $id = decrypt($request->course_child_category_id);
                $entry = $model::find($id);

                if (! $entry) {
                    Controller::db()::rollback();

                    return [
                        'status' => false,
                        'message' => __('Course not found with the given ID.'),
                    ];
                }

                $entry->updated_by = $cmp_and_user[0];
                $message = "Updated course category - {$entry->category_name}";

            } else {

                // CREATE
                $entry = new $model;
                $entry->created_by = $cmp_and_user[0];
                $message = "Created course category - {$request->category_name}";
            }

            // FILL DATA
            $entry->category_name = $request->category_name;
            $entry->page_link = $request->page_link;
            $entry->main_category_id = $request->parent_category_id;
            $entry->short_description = $request->short_description;
            $entry->scholaracad_advantage = $request->scholaracad_advantage;
            $entry->position = $request->position;
            $entry->status = $request->status;

            $entry->company_id = $cmp_and_user[1];
            $entry->created_by = $cmp_and_user[0];

            $entry->save();


            Controller::db()::commit();

            return [
                'status' => true,
                'message' => $message,
                'data' => $entry,
            ];

        } catch (\Exception $e) {

            Controller::db()::rollback();
            throw $e;
        }
    }




}
