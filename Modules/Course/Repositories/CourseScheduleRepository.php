<?php

namespace Modules\Course\Repositories;

use App\Http\Controllers\Controller;
use Modules\Course\Models\CourseSchedule;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CourseScheduleRepository extends Controller
{
    public function CourseScheduleList()
    {
        $companyId = Controller::loginandcompany();

         Controller::model('CourseSchedule', 'Course')::where('company_id', $companyId[1])
        ->where('status', 'Public')
        ->whereDate('class_end_date', '<', now())
        ->update(['status' => 'Expired']);

        return Controller::model('CourseSchedule', 'Course')::with('Category')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

public static function CourseScheduleFilters($query, $request)
{
    // HARDCODE company_id from your DB
    $query->where('company_id', 1);

    return $query
        ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] == 'course_schedule_id', function ($query) use ($request) {
            $query->where('course_schedule_id', $request->custom_filter[3]);
        })
        ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] == 'course_title', function ($query) use ($request) {
            $courseName = trim($request->custom_filter[1] ?? '');
            if (!empty($courseName)) {
                $query->whereHas('coursedate', function ($subQuery) use ($courseName) {
                    $subQuery->where('course_title', 'LIKE', '%' . $courseName . '%');
                });
            }
        });
}




    public function CourseScheduleCreate($id = null, $view = null)
    {
        try {
            $data = null;




            if ($id) {

                $id = decrypt($id);
                $data = Controller::model('CourseSchedule', 'Course')::find($id);


                if (! $data) {
                    return redirect()->back()->with('error', 'Course not found with the given ID.');
                }
                if ($view == 'view') {
                    $companyId = Controller::loginandcompany();
                    $categories = masterParentData('Category', $companyId[1]);
                     $courses = Controller::model('Course', 'Course')::get();
                     $units = masterParentData('Unit', $companyId[1]);
                        $location = Controller::model('CourseCountry', 'Course')::get();
                        $brand = masterParentData('Brands', $companyId[1]);
                        $sites = masterParentData('Site', $companyId[1]);
            $countries = Controller::model('CourseCountry', 'Course')::get();
                     $plans = DB::table('tbl_plans')
                            ->leftJoin('tbl_plan_mappings', 'tbl_plans.id', '=', 'tbl_plan_mappings.plan_id')
                            ->select('tbl_plans.*', 'tbl_plan_mappings.*')
                            ->get();




                    return view('Course::CourseSchedule.view', compact('data', 'courses' , 'categories' , 'countries','plans'));
                }
            }
            $companyId = Controller::loginandcompany();
            $categories = masterParentData('Category', $companyId[1]);
            $units = masterParentData('Unit', $companyId[1]);
            $location = Controller::model('CourseCountry', 'Course')::get();
            $brand = masterParentData('Brands', $companyId[1]);
            $sites = masterParentData('Site', $companyId[1]);
            $countries = Controller::model('CourseCountry', 'Course')::get();
            $courses = Controller::model('Course', 'Course')::get();
            $regions = Controller::model('CourseRegion' , 'Course')::get();
            // dd($regions);
            $plans = DB::table('tbl_plans')
            ->leftJoin('tbl_plan_mappings', 'tbl_plans.id', '=', 'tbl_plan_mappings.plan_id')
            ->select('tbl_plans.*', 'tbl_plan_mappings.*')
            ->get();




            return view('Course::CourseSchedule.add', compact('categories','plans','regions' , 'courses' ,'countries' , 'units', 'data', 'sites', 'location', 'brand'));
        } catch (\Exception $e) {
            dd($e);
            Controller::db()::rollback();
            catch_log($e, 'Course Course');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

//  public function CourseScheduleStore($id = null, $request)
//     {
//         $modelClass = Controller::model('CourseSchedule', 'Course');
//         $cmp_and_user = Controller::loginandcompany();
//         try {
//             DB::beginTransaction();

//             // 🔥 FIX 1: Parse arrays correctly
//             $countryIds = array_values((array) ($request["country_id"] ?? []));
//             $planIdsRaw = $request["plan_id"] ?? [];
//             $planIds = is_array($planIdsRaw) ? array_values($planIdsRaw) : [(int) $planIdsRaw];
//             $planFees = $request["plan_fees"] ?? [];
//             $planDiscounts = $request["plan_discounts"] ?? [];
//             $rangesString = $request["selected_ranges"] ?? '[]';
//             $ranges = json_decode($rangesString, true) ?? [];

//             // 🔥 EDIT MODE - Single record update
//             if ($id) {
//                 $decryptedId = is_numeric($id) ? $id : decrypt($id);
//                 $entry = $modelClass::find($decryptedId);

//                 if (!$entry) {
//                     return ['status' => false, 'message' => 'Schedule not found with ID: ' . $decryptedId];
//                 }

//                 // 🔥 FIX 2: Convert dates from "14/1/2026" → "2026-01-14"
//                 $startDate = $ranges[0]['starts'] ?? null;
//                 $endDate = $ranges[0]['ends'] ?? null;

//                 $formattedStartDate = $startDate ?
//                     \DateTime::createFromFormat('j/n/Y', $startDate)->format('Y-m-d') :
//                     $entry->class_start_date;

//                 $formattedEndDate = $endDate ?
//                     \DateTime::createFromFormat('j/n/Y', $endDate)->format('Y-m-d') :
//                     $entry->class_end_date;





//                 $entry->update([
//                     'course_id' => (int) $request["course_id"],
//                     'plan_id' => (int) ($planIds[0] ?? $entry->plan_id),
//                     'country_id' => (int) ($countryIds[0] ?? $entry->country_id),
//                     'course_includes' => json_encode($request["includes"] ?? []),
//                     'class_start_date' => $formattedStartDate,
//                     'class_end_date' => $formattedEndDate,
//                     'class_start_time' => $request["start_time"] ?: '09:00:00',
//                     'class_end_time' => $request["end_time"] ?: '17:00:00',
//                     'class_language' => $request["language"] ?? 'English',
//                     'course_fees' =>  ($request["course_fee"] ?? 0),
//                     'discount_amt' => ($request["DiscountAmount"] ?? 0),
//                     'fee_currency' => $request["feeCurrency"] ?? 'USD',
//                     'status' => $request["status"] ?? 'Public',
//                     'discount_end_date' => $discountenddate,
//                     'enroll_end_date' => $request["EnrollLastDate"] ?? null,
//                     'discount_offer_for' => $request["discount"] ?? null,
//                     'company_id' => $request["company_id"] ?? $entry->company_id,
//                     'updated_by' => $cmp_and_user[0] ?? null,
//                 ]);

//                 DB::commit();

//                 return [
//                     'status' => true,
//                     'message' => 'Schedule updated successfully!',
//                     'data' => $entry->fresh()
//                 ];
//             }

//             // 🔥 CREATE MODE - Multiple entries
//             if (empty($countryIds) || empty($planIds) || empty($ranges)) {
//                 return ['status' => false, 'message' => 'Country, plan, and date range required.'];
//             }

//             $createdEntries = [];
//             foreach ($ranges as $range) {
//                 $classStartDate = \DateTime::createFromFormat('j/n/Y', $range['starts'])->format('Y-m-d');
//                 $classEndDate = \DateTime::createFromFormat('j/n/Y', $range['ends'])->format('Y-m-d');

//                 $discountenddate =   ($classStartDate ?
//                         date('Y-m-d', strtotime($classStartDate . ' -1 day')) :
//                         null
//                     );

//                 $enrollenddate = ($classStartDate ?
//                 date('Y-m-d 23:50:00', strtotime($classStartDate . ' -1 day')) :
//                 null
//             );


//         // dd($discountenddate);

//         foreach ($planIds as $planId) {
//             foreach ($countryIds as $countryId) {

//                 $entry = new $modelClass();

//                 $uuid = Uuid::uuid7();
//                 $randomSuffix = substr(md5(microtime(true) . rand()), 0, 4);

//                 $entry->course_schedule_id =
//                     '0BSLKFW5D' . substr($uuid->toString(), 0, 8) . $randomSuffix;

//                 $entry->fill([
//                     'plan_id'             => (int) $planId,
//                     'course_id'           => (int) $request['course_id'],
//                     'country_id'          => (int) $countryId,
//                     'course_includes'     => json_encode($request['includes'] ?? []),
//                     'class_start_date'    => $classStartDate,
//                     'class_end_date'      => $classEndDate,
//                     'class_start_time'    => date('H:i:s', strtotime($request['start_time'] ?? '00:00:00')),
//                     'class_end_time'      => date('H:i:s', strtotime($request['end_time'] ?? '00:00:00')),
//                     'class_language'      => $request['language'] ?? 'English',
//                     'batch_type'          => $request['batch_type'],
//                     'course_fees'         => $request['course_fee'],
//                     'discount_amt'        => $request['DiscountAmount'],
//                     'discount_end_date'   => $discountenddate,
//                     'enroll_end_date'     => $enrollenddate,
//                     'discount_offer_for'  => $request['discount'] ?? null,
//                     'fee_currency'        => $request['feeCurrency'] ?? 'USD',
//                     'status'              => $request['status'] ?? 'Public',
//                     'created_by'          => $request['created_by'] ?? $cmp_and_user[0],
//                     'company_id'          => $request['company_id'],
//                 ])->save();

//                 $createdEntries[] = $entry;
//             }
//             }

//             }

//             DB::commit();
//             $totalEntries = count($ranges) * count($planIds) * count($countryIds);
//             return [
//                 'status' => true,
//                 'message' => "Created {$totalEntries} schedules successfully!",
//                 'data' => $createdEntries,
//             ];

//             } catch (\Exception $e) {
//                 DB::rollback();
//                 catch_log($e, "Course schedule Store");
//                 return ['status' => false, 'message' => $e->getMessage()];
//             }
//     }

public function CourseScheduleStore($id = null, $request)
{
    $modelClass = Controller::model('CourseSchedule', 'Course');
    $cmp_and_user = Controller::loginandcompany();

    // dd($request);
    try {
        DB::beginTransaction();

        $countryIds = array_values((array) ($request["country_id"] ?? []));
        $planIdsRaw = $request["plan_id"] ?? [];
        $planIds = is_array($planIdsRaw) ? array_values($planIdsRaw) : [(int) $planIdsRaw];
        $rangesString = $request["selected_ranges"] ?? '[]';
        $rangesData = json_decode($rangesString, true) ?? [];


        if ($id) {
            $decryptedId = is_numeric($id) ? $id : decrypt($id);
            $entry = $modelClass::find($decryptedId);

            if (!$entry) {
                return ['status' => false, 'message' => 'Schedule not found with ID: ' . $decryptedId];
            }

            // Use first available date for edit mode
            $startDate = null;
            $endDate = null;
            if (!empty($rangesData['weekday_ranges'])) {
                $startDate = $rangesData['weekday_ranges'][0]['starts'] ?? null;
                $endDate = $rangesData['weekday_ranges'][0]['ends'] ?? null;
            } elseif (!empty($rangesData['weekend_dates'])) {
                $firstDate = $rangesData['weekend_dates'][0];
                $startDate = "{$firstDate['year']}-{$firstDate['month']}-{$firstDate['day']}";
                $endDate = $startDate; // Single date for edit
            }

            $formattedStartDate = $startDate ?
                \DateTime::createFromFormat('Y-m-d', $startDate)->format('Y-m-d') :
                $entry->class_start_date;

            $formattedEndDate = $endDate ?
                \DateTime::createFromFormat('Y-m-d', $endDate)->format('Y-m-d') :
                $entry->class_end_date;

            $discountenddate = $formattedStartDate ?
                date('Y-m-d', strtotime($formattedStartDate . ' -1 day')) :
                $entry->discount_end_date;

            $entry->update([
                'course_id' => (int) $request["course_id"],
                'plan_id' => (int) ($planIds[0] ?? $entry->plan_id),
                'country_id' => (int) ($countryIds[0] ?? $entry->country_id),
                'course_includes' => json_encode($request["includes"] ?? []),
                'class_start_date' => $formattedStartDate,
                'class_end_date' => $formattedEndDate,
                'class_start_time' => $request["start_time"] ?: '09:00:00',
                'class_end_time' => $request["end_time"] ?: '17:00:00',
                'class_language' => $request["language"] ?? 'English',
                'batch_type' => $request["batch_type"] ?? 'weekday',
                'course_fees' => ($request["course_fee"] ?? 0),
                'discount_amt' => ($request["DiscountAmount"] ?? 0),
                'fee_currency' => $request["feeCurrency"] ?? 'USD',
                'status' => $request["status"] ?? 'Public',
                'discount_end_date' => $discountenddate,
                'enroll_end_date' => $request["EnrollLastDate"] ?? null,
                'discount_offer_for' => $request["discount"] ?? null,
                'company_id' => $request["company_id"] ?? $entry->company_id,
                'updated_by' => $cmp_and_user[0] ?? null,
            ]);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Schedule updated successfully!',
                'data' => $entry->fresh()
            ];
        }

        // 🔥 CREATE MODE - Validate basic inputs
        if (empty($countryIds) || empty($planIds) || empty($rangesData)) {
            DB::rollback();
            return ['status' => false, 'message' => 'Country, plan, and date range required.'];
        }

        $batchType = $request['batch_type'] ?? 'weekday';
        $weekdayRanges = $rangesData['weekday_ranges'] ?? [];
        $weekendDates = $rangesData['weekend_dates'] ?? [];
        $createdEntries = [];

        // 🔥 DYNAMIC LOGIC BASED ON BATCH TYPE AND AVAILABLE DATA
        if ($batchType === 'weekend') {
            // Weekend batch: Use weekend_dates (REQUIRED)
            if (empty($weekendDates)) {
                DB::rollback();
                return ['status' => false, 'message' => 'Weekend dates required for weekend batch.'];
            }

            $firstWeekendDate = $weekendDates[0];
            $lastWeekendDate = end($weekendDates);

            $classStartDate = \DateTime::createFromFormat('Y-m-d', "{$firstWeekendDate['year']}-{$firstWeekendDate['month']}-{$firstWeekendDate['day']}")->format('Y-m-d');
            $classEndDate = \DateTime::createFromFormat('Y-m-d', "{$lastWeekendDate['year']}-{$lastWeekendDate['month']}-{$lastWeekendDate['day']}")->format('Y-m-d');

            $discountenddate = $classStartDate ? date('Y-m-d', strtotime($classStartDate . ' -1 day')) : null;
            $enrollenddate = $classStartDate ? date('Y-m-d 23:50:00', strtotime($classStartDate . ' -1 day')) : null;

            foreach ($planIds as $planId) {
                foreach ($countryIds as $countryId) {
                    $entry = $this->createScheduleEntry($modelClass, $request, $planId, $countryId, $classStartDate, $classEndDate, $discountenddate, $enrollenddate, $cmp_and_user);
                    $createdEntries[] = $entry;
                }
            }

        } else {
            // Weekday batch: Use weekday_ranges OR weekend_dates as fallback
            $rangesToUse = !empty($weekdayRanges) ? $weekdayRanges : $weekendDates;

            if (empty($rangesToUse)) {
                DB::rollback();
                return ['status' => false, 'message' => 'Date ranges required for weekday batch.'];
            }

            foreach ($rangesToUse as $range) {
                if (isset($range['starts']) && isset($range['ends'])) {
                    // Weekday range format: {starts: "14/1/2026", ends: "20/1/2026"}
                    $classStartDate = \DateTime::createFromFormat('j/n/Y', $range['starts'])->format('Y-m-d');
                    $classEndDate = \DateTime::createFromFormat('j/n/Y', $range['ends'])->format('Y-m-d');
                } else {
                    // Weekend date format: {year:2026, month:2, day:7}
                    $dateStr = "{$range['year']}-{$range['month']}-{$range['day']}";
                    $classStartDate = \DateTime::createFromFormat('Y-m-d', $dateStr)->format('Y-m-d');
                    $classEndDate = $classStartDate;
                }

                $discountenddate = $classStartDate ? date('Y-m-d', strtotime($classStartDate . ' -1 day')) : null;
                $enrollenddate = $classStartDate ? date('Y-m-d 23:50:00', strtotime($classStartDate . ' -1 day')) : null;

                foreach ($planIds as $planId) {
                    foreach ($countryIds as $countryId) {
                        $entry = $this->createScheduleEntry($modelClass, $request, $planId, $countryId, $classStartDate, $classEndDate, $discountenddate, $enrollenddate, $cmp_and_user);
                        $createdEntries[] = $entry;
                    }
                }
            }
        }

        DB::commit();
        $totalEntries = count($createdEntries);

        $batchMsg = ($batchType === 'weekend') ? 'weekend' : 'weekday';
        return [
            'status' => true,
            'message' => "Created {$totalEntries} {$batchMsg} schedules successfully!",
            'data' => $createdEntries,
        ];

    } catch (\Exception $e) {
        DB::rollback();
        catch_log($e, "Course schedule Store");
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

private function createScheduleEntry($modelClass, $request, $planId, $countryId, $classStartDate, $classEndDate, $discountenddate, $enrollenddate, $cmp_and_user)
{
    $entry = new $modelClass();
    $uuid = Uuid::uuid7();
    $randomSuffix = substr(md5(microtime(true) . rand()), 0, 4);

    $entry->course_schedule_id = '0BSLKFW5D' . substr($uuid->toString(), 0, 8) . $randomSuffix;

    $entry->fill([
        'plan_id'            => (int) $planId,
        'course_id'          => (int) $request['course_id'],
        'country_id'         => (int) $countryId,
        'course_includes'    => json_encode($request['includes'] ?? []),
        'class_start_date'   => $classStartDate,
        'class_end_date'     => $classEndDate,
        'class_start_time'   => date('H:i:s', strtotime($request['start_time'] ?? '00:00:00')),
        'class_end_time'     => date('H:i:s', strtotime($request['end_time'] ?? '00:00:00')),
        'class_language'     => $request['language'] ?? 'English',
        'batch_type' => $request['batch_type'] ?? 'weekday',
        'course_fees'        => $request['course_fee'],
        'discount_amt'       => $request['DiscountAmount'],
        'discount_end_date'  => $discountenddate,
        'enroll_end_date'    => $enrollenddate,
        'discount_offer_for' => $request['discount'] ?? null,
        'fee_currency'       => $request['feeCurrency'] ?? 'USD',
        'status'             => $request['status'] ?? 'Public',
        'created_by'         => $request['created_by'] ?? $cmp_and_user[0],
        'company_id'         => $request['company_id'],
    ])->save();

    return $entry;
}





    public function CourseScheduleDestroy($id)
    {
        try {
            $id = decrypt($id);
            $model = Controller::model('CourseSchedule', 'Course');
            $entry = $model::find($id);

            if (!$entry) {
                return [
                    'status' => false,
                    'message' => 'CourseSchedule not found.',
                ];
            }

            // $enrollExists = Controller::model('Enrollment')->where('schedule_id', $entry->course_schedule_id)->exists();
            $model = Controller::model('Enrollment');
            $enrollExists = $model::where('schedule_id', $entry->course_schedule_id)->exists();


            if ($enrollExists) {
                return [
                    'status' => false,
                    'message' => 'This schedule is purchased and cannot be deleted.',
                ];
            }

            $entry->delete();
            return [
                'status' => true,
                'message' => 'Course Schedule deleted successfully.',
            ];
        } catch (\Exception $e) {
            catch_log($e, "Delete Course Schedule");
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function CourseScheduleBulkDestroy($request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return [
                    'status' => false,
                    'message' => 'No Course Schedule IDs provided.',
                    'deleted' => 0
                ];
            }

            $plainIds = array_filter(array_map('intval', $ids), function ($id) {
                return $id > 0;
            });

            if (empty($plainIds)) {
                return [
                    'status' => false,
                    'message' => 'No valid Course Schedule IDs provided.',
                    'deleted' => 0
                ];
            }

            $deletedCount = 0;
            $skippedCount = 0;

            foreach ($plainIds as $id) {

                $schedule = CourseSchedule::find($id);

                if (!$schedule) {
                    continue;
                }

                $isPurchased = Enrollment::where('schedule_id', $schedule->course_schedule_id)
                                    ->exists();

                if ($isPurchased) {
                    $skippedCount++;
                    continue;
                }

                $schedule->delete();
                $deletedCount++;
            }

            return [
                'status' => true,
                'message' => "$deletedCount deleted, $skippedCount skipped (purchased).",
                'deleted' => $deletedCount,
                'skipped' => $skippedCount
            ];

        } catch (\Exception $e) {

            catch_log($e, "Bulk Delete Course Schedule");

            return [
                'status' => false,
                'message' => 'Failed to delete Course Schedules.',
                'deleted' => 0
            ];
        }
    }











    // / Course what is learn about us

    public function whatisLearnAboutList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('Course_Whatyoulearn', 'Course')::with('country', 'Coursedate', 'state_date')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function whatisLearnAboutFilters($query, $request)
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

    public function whatisLearnAbouCreate($id = null, $view = null)
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
            $categories = masterParentData('Category', $companyId[1]);
            $units = masterParentData('Unit', $companyId[1]);
            $location = Controller::model('CourseCountry', 'Course')::get();
            $brand = masterParentData('Brands', $companyId[1]);
            $sites = masterParentData('Site', $companyId[1]);

            return view('Course::CourseSchedule.add', compact('categories', 'units', 'data', 'sites', 'location', 'brand'));
        } catch (\Exception $e) {
            dd($e);
            Controller::db()::rollback();
            catch_log($e, 'Course Course');

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function whatisLearnAbouStore($request)
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
        $entry->name = $request->name;
        $entry->description = $request->description;
        $entry->category_id = $request->category_id;
        $entry->unit_id = $request->unit_id;
        $entry->brand_name = $request->brand_name;
        $entry->model_no = $request->model_no;
        $entry->cost_price = $request->cost_price;
        $entry->selling_price = $request->selling_price;
        // $entry->reorder_level = $request->reorder_level;
        $entry->safety_stock = $request->safety_stock;
        // $entry->lead_time_days = $request->lead_time_days;
        $entry->company_id = $cmp_and_user[1];
        $entry->status = $request->status;
        $entry->site_id = $request->site_id;
        $entry->warehouse_id = $request->warehouse_id;
        $entry->location_id = $request->location_id;
        $entry->subcategory_id = $request->subcategory_id;
        $entry->additional_information = $request->additional_information;
        // $entry->tax_type_id = $request->tax_type_id;
        // $entry->tax_id = $request->tax_id;
        $entry->save();

        if ($request->hasFile('gallery_images')) {
            $gallery = Controller::model('CourseImages', 'Course');

            // Delete old images for this Course
            $gallery::where('Course_id', $entry->id)->delete();

            foreach ($request->file('gallery_images') as $file) {
                $filename = Controller::helper('CommonHelper')::folder_name($entry->id, 'CourseCourse', 'Course', $file, 15, 'images', 'Course');
                // dd($filename);
                $gallery_images = new $gallery;
                $gallery_images->Course_id = $entry->id;
                $gallery_images->attachment_name = $filename[1];
                $gallery_images->attachment_type = $file->getClientMimeType();
                $gallery_images->created_by = $cmp_and_user[0];
                $gallery_images->company_id = $cmp_and_user[1];
                $gallery_images->save();
            }
        }

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
        if ($request->hasFile('brochure_file')) {
            $brochure = Controller::helper('CommonHelper')::folder_name($entry->id, 'CourseCourse', 'Course', $request->file('brochure_file'), 15, 'image_document', 'Course');
            if (empty($brochure)) {
                Controller::db()::rollback();

                return [
                    'status' => false,
                    'message' => __('Course.folder-name-not-generating of brochure'),
                ];
            }
            $entry->brochure = $brochure[1];
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

        // } catch (\Exception $e) {
        //     Controller::db()::rollback();
        //     catch_log($e,"Course Course");
        //     return [
        //         'status' => false,
        //         'message' => $e->getMessage(),
        //     ];
        // }
    }

    // / About Geography

    public function aboutGeographyList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('AboutGeography', 'Course')::with('country', 'state_date')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function aboutGeographyFilters($query, $request)
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

    // / Course Read More Certificate`

    public function CourseReadMoreList()
    {
        $companyId = Controller::loginandcompany();

        return Controller::model('CourseReadMore', 'Course')::with('country', 'Coursedate', 'state_date')->where('company_id', $companyId[1])->orderby('id', 'DESC');
    }

    public static function CourseReadMoreFilters($query, $request)
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
}
