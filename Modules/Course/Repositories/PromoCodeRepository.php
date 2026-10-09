<?php

namespace Modules\Course\Repositories;
use App\Http\Controllers\Controller;
use Modules\Course\Models\PromoCode;
use Illuminate\Support\Facades\DB;
use Modules\Course\Models\Course;

class PromoCodeRepository extends Controller
{
    protected $model;

    public function __construct(PromoCode $model)
    {
        $this->model = $model;
    }

    public function PromoCodeList()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('PromoCode', 'Course')::with('Coursedate','country')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function PromoCodeFilters($query, $request)
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

    public function PromoCodeCreate($id = null, $view = null)
    {
        try {
            $data = null;
            $multidat = collect();

            if ($id) {
                $id = decrypt($id);
                $data = Controller::model('PromoCode', 'Course')::with('Coursedate')->find($id);
                if (!$data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }

                $multidat = Controller::model('PromoCode', 'Course')
                    ::where('course_id', $data->course_id)
                    ->get();

            }
            $multidat = Controller::model('PromoCode', 'Course')
                ::where('course_id', $data?->course_id)
                ->get();
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            return view('Course::promo-code.create', compact('courses', 'data', 'multidat' , 'countries' ));
        } catch (\Exception $e) {
            Controller::db()::rollback();
            catch_log($e, "Course promo Code");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // public function PromoCodeStore($id = null, $data)
    // {
    //     dd($data);
    //     $model = Controller::model('PromoCode', 'Course');
    //     $cmp_and_user = Controller::loginandcompany();

    //     try {
    //         $incomingPromocode = $data['promocode'];
    //         $existingActivePromo = $model::where('promocode', $incomingPromocode)
    //             ->where('status', 'active')
    //             ->first();

    //         if ($id && $existingActivePromo && $existingActivePromo->id == $id) {
    //         } elseif ($existingActivePromo) {
    //             return [
    //                 'status' => false,
    //                 'message' => "Promo code '{$incomingPromocode}' is already active and in use.",
    //             ];
    //         }

    //         if ($id) {
    //             $entry = $model::find($id);
    //             if (!$entry) {
    //                 return [
    //                     'status' => false,
    //                     'message' => __('Course not found with the given ID.'),
    //                 ];
    //             }
    //         } else {
    //             $entry = new $model();
    //         }

    //         $entry->promocode = $data['promocode'];
    //         $entry->text = $data['text'];
    //         $entry->course_id = $data['course_id'] ?? null;
    //         $entry->currency = $data['currency'];
    //         $entry->discount_percentage = $data['discount_percentage'];
    //         $entry->status = $data['status'];
    //         $entry->start_date = $data['start_date'] ?? null;
    //         $entry->end_date = $data['end_date'] ?? null;
    //         $entry->created_by = $cmp_and_user[0];
    //         $entry->company_id = $cmp_and_user[1];
    //         $entry->updated_by = $cmp_and_user[0];
    //         $entry->save();

    //         $message = $id
    //             ? 'Updated Course Promo Code for Course - ' . $data['course_id']
    //             : 'Created Course Promo Code for Course - ' . $data['course_id'];

    //         addToLog('PromoCode', $data['course_id'], $message);

    //         return [
    //             'status' => true,
    //             'message' => 'Promo Code saved successfully.',
    //             'data' => $entry,
    //         ];
    //     } catch (\Exception $e) {
    //         Controller::db()::rollback();
    //         catch_log($e, "Promo Code Store");
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }

    // public function PromoCodeStore($id = null, $data)
    // {
    //     $model = Controller::model('PromoCode', 'Course');
    //     $cmp_and_user = Controller::loginandcompany();

    //     try {
    //         $courseIds = $data['course_id'] ?? [];
    //         $daterange = $data['daterange'][0] ?? '';

    //         if (empty($courseIds)) {
    //             return [
    //                 'status' => false,
    //                 'message' => 'At least one course is required.',
    //             ];
    //         }

    //         $dates = explode(' - ', $daterange);
    //         $startDate = !empty($dates[0]) ? date('Y-m-d', strtotime(trim($dates[0]))) : null;
    //         $endDate = !empty($dates[1]) ? date('Y-m-d', strtotime(trim($dates[1]))) : null;

    //         $createdEntries = [];


    //         foreach ($courseIds as $courseId) {
    //             $incomingPromocode = $data['promocode'];


    //             $existingActivePromo = $model::where('promocode', $incomingPromocode)
    //                 ->where('status', 'active')
    //                 ->where('course_id', $courseId)
    //                 ->where('company_id', $cmp_and_user[1])
    //                 ->first();

    //             if ($existingActivePromo) {
    //                 continue;
    //             }

    //             $entry = new $model();

    //             $entry->promocode = $data['promocode'];
    //             $entry->text = $data['text'];
    //             $entry->course_id = $courseId;
    //             $entry->discount_Percentage = $data['discount_percentage'];
    //             $entry->status = $data['status'] == 'Active' ? 'active' : strtolower($data['status']);
    //             $entry->start_date = $startDate;
    //             $entry->end_date = $endDate;
    //             $entry->created_by = $cmp_and_user[0];
    //             $entry->company_id = $data['company_id'] ?? $cmp_and_user[1];
    //             $entry->updated_by = $cmp_and_user[0];

    //             $entry->save();
    //             $createdEntries[] = $entry;
    //         }

    //         if (empty($createdEntries)) {
    //             return [
    //                 'status' => false,
    //                 'message' => 'No new promo codes created (already exist for selected courses).',
    //             ];
    //         }

    //         // Log first entry
    //         addToLog('PromoCode', $courseIds[0], 'Created ' . count($createdEntries) . ' promo codes for courses: ' . implode(', ', $courseIds));

    //         return [
    //             'status' => true,
    //             'message' => count($createdEntries) . ' Promo Code(s) saved successfully for ' . count($courseIds) . ' course(s).',
    //             'data' => $createdEntries,
    //         ];

    //     } catch (\Exception $e) {
    //         Controller::db()::rollback();
    //         catch_log($e, "Promo Code Store");
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }

public function PromoCodeStore($id = null, $data)
{
    // dd($data);
    $model = Controller::model('PromoCode', 'Course');
    $cmp_and_user = Controller::loginandcompany();

    try {
        $courseIds = $data['course_id'] ?? [];
        $isSelectAll = $data['select_all_courses'] ?? '0';
        $daterange = $data['daterange'][0] ?? '';

        if (empty($courseIds) && $isSelectAll != '1') {
            return [
                'status' => false,
                'message' => 'At least one course is required.',
            ];
        }

        $dates = explode(' - ', $daterange);
        $startDate = !empty($dates[0]) ? date('Y-m-d', strtotime(trim($dates[0]))) : null;
        $endDate = !empty($dates[1]) ? date('Y-m-d', strtotime(trim($dates[1]))) : null;

        $incomingPromocode = $data['promocode'];
        $companyId = $data['company_id'] ?? $cmp_and_user[1];
        $createdEntries = [];

        // **EDIT MODE: Update existing promo code**
if ($id) {
    $existing = $model::findOrFail($id);

    if ($existing->company_id != $companyId || $existing->created_by != $cmp_and_user[0]) {
        return [
            'status' => false,
            'message' => 'Unauthorized access to this promo code.',
        ];
    }

    // 🔥 COURSE HANDLING
    if ($isSelectAll == '1') {
        $existing->course_id = null;
        $existing->course_type = 'ALL';
    } else {
        $courseId = is_array($courseIds) ? $courseIds[0] : $courseIds;

        $course = Course::find($courseId);

        if (!$course) {
            return [
                'status' => false,
                'message' => 'Invalid course selected.',
            ];
        }

        $existing->course_id = $courseId;
        $existing->course_type = $course->course_title ;
    }

    $existing->promocode = $incomingPromocode;
    $existing->text = $data['text'];
    $existing->discount_Percentage = $data['discount_percentage'];
    $existing->status = strtolower($data['status']);
    $existing->start_date = $startDate;
    $existing->end_date = $endDate;
    $existing->promo_type = $data['promo_type'];;
    $existing->updated_by = $cmp_and_user[0];
    $existing->save();

    return [
        'status' => true,
        'message' => 'Promo code updated successfully!',
        'data' => [$existing],
    ];
}


        // **CREATE MODE: Your original logic with fix**
if ($isSelectAll == '1') {

    $existingActivePromo = $model::where('promocode', $incomingPromocode)
        ->where('status', 'active')
        ->whereNull('course_id')
        ->where('company_id', $companyId)
        ->first();

    if (!$existingActivePromo) {
        $entry = new $model();
        $entry->promocode = $incomingPromocode;
        $entry->text = $data['text'];
        $entry->course_id = null;
        $entry->course_type = 'ALL'; // 🔥
        $entry->discount_Percentage = $data['discount_percentage'];
        $entry->status = strtolower($data['status']);
        $entry->start_date = $startDate;
        $entry->end_date = $endDate;
        $entry->promo_type = $data['promo_type'];
        $entry->created_by = $cmp_and_user[0];
        $entry->company_id = $companyId;
        $entry->updated_by = $cmp_and_user[0];
        $entry->save();

        $createdEntries[] = $entry;
    }

}
 else {

    $courseId = is_array($courseIds) ? $courseIds[0] : $courseIds;
    $course = Course::find($courseId);

    if (!$course) {
        return [
            'status' => false,
            'message' => 'Invalid course selected.',
        ];
    }

    $existingActivePromo = $model::where('promocode', $incomingPromocode)
        ->where('status', 'active')
        ->where('course_id', $courseId)
        ->where('company_id', $companyId)
        ->first();

    if (!$existingActivePromo) {
        $entry = new $model();
        $entry->promocode = $incomingPromocode;
        $entry->text = $data['text'];
        $entry->course_id = $courseId;
        $entry->course_type = $course->course_title ?? $course->name; // 🔥
        $entry->discount_Percentage = $data['discount_percentage'];
        $entry->status = strtolower($data['status']);
        $entry->start_date = $startDate;
        $entry->end_date = $endDate;
        $entry->created_by = $cmp_and_user[0];
        $entry->company_id = $companyId;
        $entry->updated_by = $cmp_and_user[0];
        $entry->save();

        $createdEntries[] = $entry;
    }
}

        // No new records created
        if (empty($createdEntries)) {
            $message = $isSelectAll == '1'
                ? 'Promo code already exists for all courses.'
                : 'No new promo codes created (already exist).';
            return [
                'status' => false,
                'message' => $message,
            ];
        }

        // Success - log and return
        $logCourseId = $isSelectAll == '1' ? 0 : $courseIds[0];
        $message = 'Created ' . count($createdEntries) . ' promo codes for: ' .
            ($isSelectAll == '1' ? 'ALL COURSES' : implode(', ', $courseIds));

        addToLog('PromoCode', $logCourseId, $message);

        $successMessage = $isSelectAll == '1'
            ? 'Promo code saved for ALL courses successfully!'
            : count($createdEntries) . ' Promo Code(s) saved for ' . count($courseIds) . ' course(s).';

        return [
            'status' => true,
            'message' => $successMessage,
            'data' => $createdEntries,
        ];

    } catch (\Exception $e) {
        Controller::db()::rollback();
        catch_log($e, "Promo Code Store");
        return [
            'status' => false,
            'message' => $e->getMessage(),
        ];
    }
}




}
