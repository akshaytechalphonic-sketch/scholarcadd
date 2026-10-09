<?php

namespace Modules\Course\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Models\Course;
use App\Traits\ApiResponseTrait;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Models\CourseCountry;
use Modules\Course\Models\City;
use Modules\DynamicMaster\Models\PlanMapping;
use Modules\DynamicMaster\Models\Plan;
use Modules\Course\Models\DynamicCoursePages;
use Validator;

class CourseController extends Controller
{
    use ApiResponseTrait;

    public function category_and_course(Request $request)
    {
        try {

         $validator = Validator::make($request->all(), [
                'country_id' => 'nullable|string',
            ]);

            $all_data = self::model('CourseCategory', 'Course')::whereNull('main_category_id')->where('status', 'Active')->get();
            if ($all_data->isEmpty()) {
                return $this->errorResponse('No Category Found', 404);
            }
            $data = [];
            foreach ($all_data as $cat) {
                $cat_course = [];
                $all_subcat_id = self::model('CourseCategory', 'Course')::where('main_category_id', $cat->id)->where('status', 'Active')->pluck('id')->toArray();
                $all_course = self::model('Course', 'Course')::where('status', 'Active')->whereIn('course_category_id', $all_subcat_id)->get();
                $country = CourseCountry::where('iso2', $request->country_id)->first();
                $plan = Plan::where('name', 'Basic plan')->first();
                $mapping = $plan ? PlanMapping::where('plan_id', $plan->id)->get() : collect();
                $value_in_dollar = $country?->value_in_dollar ?? 1;

                if ($all_course->isNotEmpty()) {
                    foreach ($all_course as $cour) {
                        $courseMapping = $mapping->where('course_id', $cour->id)->first();
                        $courseFee = $courseMapping?->course_fee ?? null;
                        $totalFee = $value_in_dollar * ($courseFee ?? 0);
                        $courseRating = $country ? DynamicCoursePages::select('googleRating', 'facebook', 'installgram', 'enrolled')
                                           ->where('country_id', $country->id)
                                           ->whereNull('state_id')
                                           ->where('course_id', $cour->id)
                                           ->first() : null;

                        $cat_course[] = [
                            'id' => $cour?->id ?? '',
                            'url_title' => $cour?->url_title ?? '',
                            'course_short_name' => $cour?->course_short_name ?? '',
                            'course_title' => $cour?->course_title ?? '',
                            'banner_bg_color' => $cour?->banner_bg_color ?? '',
                            'page_title' => $cour?->page_title ?? '',
                            'short_description' => $cour?->short_description ?? '',
                            'header_image' => $cour?->header_image ?? '',
                            'course_fee' => $totalFee,
                            'currency' => $country?->currency ?? 'USD',
                            'ratings' => $courseRating,
                            'isTranding' => $cour->is_demand_course
                        ];
                    }
                }
                $data[] = [
                    'id' => $cat?->id ?? '',
                    'category_name' => $cat?->category_name ?? '',
                    'page_link' => $cat?->page_link ?? '',
                    'short_description' => $cat?->short_description ?? '',
                    'cources' => $cat_course,
                ];
            }
            return $this->successResponse($data, 'Data fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    // public function view_course(Request $request)
    // {
    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'course_id' => 'required|integer|exists:tbl_courses,id',
    //         ]);

    //         // If validation fails, return error response
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Validation failed.',
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $countryName = '';

    //         if ($request->has('country_id')) {
    //             $country = CourseCountry::where('id', $request->country_id)->first();
    //             $countryName = $country?->name ?? '';
    //         }


    //         $course_id = $request->course_id;
    //         if ($course_id) {
    //             // $course_id = decrypt($course_id);
    //         } else {
    //             return $this->errorResponse('Course Id is required', 403);
    //         }
    //         $dat = self::model('Course', 'Course')::find($course_id);
    //         if (!$dat) {
    //             return $this->errorResponse('No Data Found', 404);
    //         }

    //         $highlight = self::model('CourseHighlights', 'Course')::where('course_id', $course_id)->first();

    //         $courseDetails = $dat?->course_details ?? '';

    //         if (!empty($countryName)) {
    //             $courseDetails = str_replace('{{country}}', $countryName, $courseDetails);
    //         }


    //         $data = [
    //             'id' => encrypt($dat?->id) ?? '',
    //             'is_demand_course' => $dat?->is_demand_course ?? '',
    //             'url_title' => $dat?->url_title ?? '',
    //             'course_short_name' => $dat?->course_short_name ?? '',
    //             'description' => $dat?->description ?? '',
    //             'course_category' => $dat?->course_category?->category_name ?? '',
    //             'course_sub_category' => $dat?->course_category?->sub_category?->category_name ?? '',
    //             'course_title' => $dat?->course_title ?? '',
    //             'short_description' => $dat?->short_description ?? '',
    //             'overview' => $dat?->overview ?? '',
    //             'header_image' => $dat?->header_image ?? '',
    //             'banner_bg_color' => $dat?->banner_bg_color ?? '',
    //             'overview1' => $dat?->overview1 ?? '',
    //             'training_options' => $dat?->training_options ?? '',
    //             'course_curriculum' => $dat?->course_curriculum ?? '',
    //             'course_details' => $courseDetails,
    //             'meta_description' => $dat?->meta_description ?? '',
    //             'meta_keywords' => $dat?->meta_keywords ?? '',
    //             'page_title' => $dat?->page_title ?? '',

    //             'highlight' => $highlight?->course_description ?? '',
    //             'bullets' => $highlight->course_highlights_in_pointers ?? '[]',

    //         ];

    //         return $this->successResponse($data, 'Data fetched successfully');
    //     } catch (\Exception $e) {
    //         return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
    //     }
    // }

    public function view_course(Request $request)
    {
        try {
            $courseIdentifier = $request->input('course_id') ?? $request->input('slug') ?? $request->input('url_title') ?? $request->input('id');

            if (!$courseIdentifier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => ['course_id' => ['The course id or slug field is required.']]
                ], 422);
            }

            $courseModel = self::model('Course', 'Course');
            if (is_numeric($courseIdentifier)) {
                $dat = $courseModel::find($courseIdentifier);
            } else {
                $dat = $courseModel::where('url_title', $courseIdentifier)->orWhere('id', $courseIdentifier)->first();
            }

            $courseId = $dat?->id ?? $courseIdentifier;
            if (!$dat) {
                return $this->errorResponse('No Data Found', 404);
            }


            $countryName = '';

            if ($request->filled('country_id')) {
                $country = CourseCountry::where('iso2', $request->country_id)->first();
                $countryName = $country?->name ?? '';
            }

            if ($request->filled('city_id')) {
                $country = City::where('id', $request->city_id)->first();
                $countryName = $country?->name ?? '';
            }

            $highlight = self::model('CourseHighlights', 'Course')
                ::where('course_id', $courseId)
                ->first();

            $data = [
                'id' => encrypt($dat?->id) ?? '',
                'is_demand_course' => $dat?->is_demand_course ?? '',
                'url_title' => $dat?->url_title ?? '',
                'course_short_name' => $dat?->course_short_name ?? '',
                'description' => $dat?->description ?? '',
                'course_category' => $dat?->course_category?->category_name ?? '',
                'course_sub_category' => $dat?->course_category?->sub_category?->category_name ?? '',
                'course_title' => $dat?->course_title ?? '',
                'short_description' => $dat?->short_description ?? '',
                'overview' => $dat?->overview ?? '',
                'header_image' => $dat?->header_image ?? '',
                'banner_bg_color' => $dat?->banner_bg_color ?? '',
                'overview1' => $dat?->overview1 ?? '',
                'training_options' => $dat?->training_options ?? '',
                'course_curriculum' => $dat?->course_curriculum ?? '',
                'course_details' => $dat?->course_details ?? '',
                'meta_description' => $dat?->meta_description ?? '',
                'meta_keywords' => $dat?->meta_keywords ?? '',
                'page_title' => $dat?->page_title ?? '',
                'highlight' => $highlight?->course_description ?? '',
                'bullets' => $highlight?->course_highlights_in_pointers ?? '[]',
            ];

            $data = array_map(function ($value) use ($countryName) {
                return $this->replaceCountry($value, $countryName);
            }, $data);

            return $this->successResponse($data, 'Data fetched successfully');

        } catch (\Exception $e) {
            return $this->errorResponse(
                'An unexpected error occurred',
                500,
                $e->getMessage()
            );
        }
    }


    public function categorisedCourse_list(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $page = $request->get('page', 1);

            $category_id = $request->get('category_id');
            $category_id = CourseCategory::where('page_link', $category_id)->first()?->id;
            $categories = CourseCategory::where('main_category_id', $category_id)->get()->pluck('id');

            $query = self::model('Course', 'Course')::with(['course_category.sub_category']);


            if ($category_id) {
                $query->whereIn('course_category_id', $categories);
            }


            $courses = $query->paginate($limit, ['*'], 'page', $page);

            if ($courses->isEmpty()) {
                return $this->errorResponse('No Courses Found', 404);
            }

            $data = $courses->map(function ($dat) {
                return [
                    'id' => $dat?->id ?? '',
                    'is_demand_course' => $dat?->is_demand_course ?? '',
                    'url_title' => $dat?->url_title ?? '',
                    'course_short_name' => $dat?->course_short_name ?? '',
                    'course_title' => $dat?->course_title ?? '',
                    'short_description' => $dat?->short_description ?? '',
                    'course_category' => $dat?->course_category?->category_name ?? '',
                    'course_sub_category' => $dat?->course_category?->sub_category?->category_name ?? '',
                    'header_image' => $dat?->header_image ?? '',
                    'banner_bg_color' => $dat?->banner_bg_color ?? '',
                ];
            });

            $meta = [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
            ];

            return $this->successResponse([
                'courses' => $data,
                'pagination' => $meta
            ], 'Courses fetched successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }



    public function allCourses(Request $request)
    {
        try {

             $validator = Validator::make($request->all(), [
                'country_id' => 'nullable|string',
            ]);


            $limit = $request->get('limit', 10);
            $page = $request->get('page', 1);


            // $query = self::model('Course', 'Course')::with(['course_category.sub_category']);
            $query = self::model('Course', 'Course')::with([
                'course_category.sub_category',
                'planMappings'
            ]);
            $courses = $query->paginate($limit, ['*'], 'page', $page);
            $country = CourseCountry::where('iso2', $request->country_id)->first();
            $currencyValue = $country?->value_in_dollar ?? 1;
            $ratings = $country ? DynamicCoursePages::where('country_id', $country->id)->get() : collect();
            if ($courses->isEmpty()) {
                return $this->errorResponse('No Courses Found', 404);
            }

            $data = $courses->map(function ($dat) use ($currencyValue, $country, $ratings) {
                $courseFee = $dat->planMappings->first()?->course_fee ?? null;
                $totalValue = $currencyValue * ($courseFee ?? 0);
                $rating = $ratings->where('course_id', $dat->id)->first();
                return [
                    'id' => $dat?->id ?? '',
                    'is_demand_course' => $dat?->is_demand_course ?? '',
                    'url_title' => $dat?->url_title ?? '',
                    'course_short_name' => $dat?->course_short_name ?? '',
                    'course_title' => $dat?->course_title ?? '',
                    'short_description' => $dat?->short_description ?? '',
                    'course_category' => $dat?->course_category?->category_name ?? '',
                    'course_sub_category' => $dat?->course_category?->sub_category?->category_name ?? '',
                    'header_image' => $dat?->header_image ?? '',
                    'banner_bg_color' => $dat?->banner_bg_color ?? '',
                    'course_fee' => $totalValue,
                    'currency' => $country?->currency ?? 'USD',
                    'ratings' => $rating
                ];
            });

            $meta = [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
            ];

            return $this->successResponse([
                'courses' => $data,
                'pagination' => $meta
            ], 'Courses fetched successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500, $e->getMessage());
        }
    }

    public function course_list(Request $request)
    {
        return $this->allCourses($request);
    }

    private function replaceCountry($value, $countryName)
    {
        if (is_string($value) && !empty($countryName)) {
            return str_replace('{{country}}', $countryName, $value);
        }

        return $value;
    }

}
