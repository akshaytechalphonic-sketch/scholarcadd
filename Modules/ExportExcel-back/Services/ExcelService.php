<?php

namespace Modules\ExportExcel\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
// use Modules\ExportExcel\Repositories\ExcelRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\File;
use Modules\Course\Models\State;
use Modules\Course\Models\CourseCountry;
use Modules\Course\Models\Course;
use Modules\FAQS\Models\FAQS_Category;
use Modules\Course\Models\DynamicCourseSectionheading;
use Modules\Course\Models\CourseOverviews;
use Modules\Course\Models\Course_Whatyoulearn;
use Modules\Course\Models\DynamicCourseKeyfeatures;
use Modules\Course\Models\DynamicCoursePages;
use Modules\Course\Models\CourseWhyScholarAcad;
use Modules\Course\Models\CertificationEligibility;
use Modules\Course\Models\CourseSalary;
use Modules\Course\Models\CourseCertificationPath;
use Modules\Course\Models\CourseBenefits;
use Modules\Course\Models\CourseLearningObjectives;
use Modules\Course\Models\Certificatetext;
use Modules\Course\Models\AboutGeography;
use Modules\Course\Models\CourseCurriculumFaq;
use Modules\Course\Models\DynamicCourseHeadline;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Modules\Course\Models\CourseHighlights;
use Carbon\Carbon;



class ExcelService extends Controller
{
    // protected ExcelRepository $repository;

    // public function __construct(ExcelRepository $repository)
    // {
    //     $this->repository = $repository;
    // }

    // public function updateFromExcel(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|mimes:xlsx,xls,csv|max:20480',
    //     ]);

    //     try {

    //         $file = $request->file('excel_file');
    //         $spreadsheet = IOFactory::load($file->getPathname());
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $rows = $sheet->toArray();

    //         // Remove header row safely
    //         $header = array_shift($rows);

    //         $updated = 0;
    //         $skipped = 0;

    //         foreach ($rows as $row) {

    //             $courseId  = $row[0] ?? null;
    //             $countryId = $row[1] ?? null;
    //             $stateId   = $row[2] ?? null;

    //             if (empty($courseId) || empty($countryId) || empty($stateId)) {
    //                 $skipped++;
    //                 continue;
    //             }
    //             $bulletPointsModel = Controller::model('CourseHighlights', 'Course');

    //             $bulletPointsModel::updateOrCreate(
    //                 [
    //                     'course_id'  => $courseId,
    //                     'country_id' => $countryId,
    //                     'state_id'   => $stateId,
    //                 ],
    //                 [
    //                     'course_description' => $row[3] ?? null,
    //                     'course_highlights_in_pointers' => $row[4] ?? null,
    //                     'updated_by' => Auth::id(),
    //                 ]
    //             );

    //             // Dynamic Headline Update
    //             DynamicCourseHeadline::updateOrCreate(
    //                 [
    //                     'course_id'  => $courseId,
    //                     'country_id' => $countryId,
    //                     'state_id'   => $stateId,
    //                 ],
    //                 [
    //                     'hone_tag'   => $row[5] ?? null,
    //                     'meta_title' => $row[6] ?? null,
    //                     'meta_keywords' => $row[7] ?? null,
    //                     'imageAltText' => $row[8] ?? null,
    //                     'metaDescription' => $row[9] ?? null,
    //                     'updated_by' => Auth::id(),
    //                 ]
    //             );

    //             $updated++;
    //         }

    //         // AJAX Response
    //         if ($request->ajax() || $request->expectsJson()) {
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => "Updated: {$updated}, Skipped: {$skipped}"
    //             ]);
    //         }

    //         return back()->with('success', "Updated: {$updated}, Skipped: {$skipped}");

    //     } catch (\Throwable $e) {

    //         Log::error('Excel import error: ' . $e->getMessage());

    //         if ($request->ajax() || $request->expectsJson()) {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Something went wrong while importing file.'
    //             ], 500);
    //         }

    //         return back()->with('error', 'Import failed. Please check the file.');
    //     }
    // }


 public function updateFromExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {

            set_time_limit(0);

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            array_shift($rows); // remove header

            DB::beginTransaction();

            $userId = Auth::id();
            $now = now();

            /*
            ==========================================
            STEP 1: CREATE UNIQUE KEY STRINGS
            ==========================================
            */

            $keyStrings = [];

            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {
                    $stateId = $row[2] ?? 'NULL';
                    $keyStrings[] = $row[0] . '-' . $row[1] . '-' . $stateId;
                }
            }

            $keyStrings = array_unique($keyStrings);

            /*
            ==========================================
            STEP 2: FETCH EXISTING RECORDS FAST
            ==========================================
            */

            $existingHighlights = CourseHighlights::select(
                    'id',
                    DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                )
                ->whereIn(
                    DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                    $keyStrings
                )
                ->get()
                ->keyBy('key_string');

            $existingHeadlines = DynamicCourseHeadline::select(
                    'id',
                    DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                )
                ->whereIn(
                    DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                    $keyStrings
                )
                ->get()
                ->keyBy('key_string');

            /*
            ==========================================
            STEP 3: PREPARE INSERT / UPDATE
            ==========================================
            */

            $insertHighlight = [];
            $insertHeadline  = [];

            foreach ($rows as $row) {

                if (empty($row[0]) || empty($row[1])) continue;

                $courseId  = $row[0];
                $countryId = $row[1];
                $stateId   = $row[2] ?? null;

                $key = $courseId . '-' . $countryId . '-' . ($stateId ?? 'NULL');

                /*
                ============================
                HIGHLIGHT TABLE
                ============================
                */

                if (isset($existingHighlights[$key])) {

                    $updateData = [];

                    if (!empty($row[3])) {
                        $updateData['course_description'] = $row[3];
                    }

                    if (!empty($row[4])) {
                        $updateData['course_highlights_in_pointers'] = $row[4];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        CourseHighlights::where('id', $existingHighlights[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertHighlight[] = [
                        'course_id' => $courseId,
                        'country_id' => $countryId,
                        'state_id' => $stateId,
                        'course_description' => $row[3] ?? null,
                        'course_highlights_in_pointers' => $row[4] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                /*
                ============================
                HEADLINE TABLE
                ============================
                */

                if (isset($existingHeadlines[$key])) {

                    $updateData = [];

                    if (!empty($row[5])) $updateData['hone_tag'] = $row[5];
                    if (!empty($row[6])) $updateData['meta_title'] = $row[6];
                    if (!empty($row[7])) $updateData['meta_keywords'] = $row[7];
                    if (!empty($row[8])) $updateData['imageAltText'] = $row[8];
                    if (!empty($row[9])) $updateData['metaDescription'] = $row[9];

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        DynamicCourseHeadline::where('id', $existingHeadlines[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertHeadline[] = [
                        'course_id' => $courseId,
                        'country_id' => $countryId,
                        'state_id' => $stateId,
                        'hone_tag' => $row[5] ?? null,
                        'meta_title' => $row[6] ?? null,
                        'meta_keywords' => $row[7] ?? null,
                        'imageAltText' => $row[8] ?? null,
                        'metaDescription' => $row[9] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            /*
            ==========================================
            STEP 4: BULK INSERT
            ==========================================
            */

            if (!empty($insertHighlight)) {
                CourseHighlights::insert($insertHighlight);
            }

            if (!empty($insertHeadline)) {
                DynamicCourseHeadline::insert($insertHeadline);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Import completed successfully'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Excel import error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCourseHighlights(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {

            set_time_limit(0);
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            array_shift($rows); // remove header row

            $userId = Auth::id();
            $now = now();

            /*
            ==========================================
            STEP 1: BUILD UNIQUE KEY STRINGS
            ==========================================
            */

            $keyStrings = [];

            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {
                    $stateId = $row[2] ?? 'NULL';
                    $keyStrings[] = $row[0] . '-' . $row[1] . '-' . $stateId;
                }
            }

            $keyStrings = array_unique($keyStrings);

            /*
            ==========================================
            STEP 2: FETCH EXISTING RECORDS
            ==========================================
            */

            $fetchRecords = function ($model) use ($keyStrings) {
                return $model::select(
                        'id',
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                    )
                    ->whereIn(
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                        $keyStrings
                    )
                    ->get()
                    ->keyBy('key_string');
            };

            $existingPages    = $fetchRecords(DynamicCoursePages::class);
            $existingSection  = $fetchRecords(DynamicCourseSectionheading::class);
            $existingOverview = $fetchRecords(CourseOverviews::class);
            $existingLearn    = $fetchRecords(Course_Whatyoulearn::class);
            $existingFeatures = $fetchRecords(DynamicCourseKeyfeatures::class);

            /*
            ==========================================
            STEP 3: PREPARE INSERT ARRAYS
            ==========================================
            */

            $insertPages    = [];
            $insertSection  = [];
            $insertOverview = [];
            $insertLearn    = [];
            $insertFeatures = [];

            foreach ($rows as $row) {

                if (empty($row[0]) || empty($row[1])) continue;

                $courseId  = (int) $row[0];
                $countryId = (int) $row[1];
                $stateId   = $row[2] ?? null;

                $key = $courseId . '-' . $countryId . '-' . ($stateId ?? 'NULL');

                /*
                ==================================================
                1️⃣ DynamicCoursePages
                ==================================================
                */
                if (isset($existingPages[$key])) {

                    // nothing to update currently (only insert if missing)

                } else {

                    $insertPages[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'is_ready'   => 'yes',
                        'company_id' => 1,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                /*
                ==================================================
                2️⃣ DynamicCourseSectionheading
                ==================================================
                */
                if (isset($existingSection[$key])) {

                    $updateData = [];

                    if (!is_null($row[3])) $updateData['program_overview'] = $row[3];
                    if (!is_null($row[4])) $updateData['training_highlights'] = $row[4];
                    if (!is_null($row[5])) $updateData['new_learning_objectives'] = $row[5];

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        DynamicCourseSectionheading::where('id', $existingSection[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertSection[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'program_overview'        => $row[3] ?? null,
                        'training_highlights'     => $row[4] ?? null,
                        'new_learning_objectives' => $row[5] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                /*
                ==================================================
                3️⃣ CourseOverviews
                ==================================================
                */
                if (isset($existingOverview[$key])) {

                    $updateData = [];

                    if (!is_null($row[6])) {
                        $updateData['course_overview'] = $row[6];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        CourseOverviews::where('id', $existingOverview[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertOverview[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'course_overview' => $row[6] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                /*
                ==================================================
                4️⃣ Course_Whatyoulearn
                ==================================================
                */
                $pointers = null;

                if (!empty($row[8])) {
                    $decoded = json_decode($row[8], true);
                    $pointers = is_array($decoded) ? $decoded : null;
                }

                if (isset($existingLearn[$key])) {

                    $updateData = [];

                    if (!is_null($row[7])) $updateData['description'] = $row[7];
                    if (!is_null($pointers)) $updateData['pointers'] = $pointers;

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        Course_Whatyoulearn::where('id', $existingLearn[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertLearn[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'description' => $row[7] ?? null,
                        'pointers'    => $pointers,
                        'created_by'  => $userId,
                        'updated_by'  => $userId,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }

                /*
                ==================================================
                5️⃣ DynamicCourseKeyfeatures
                ==================================================
                */
                if (isset($existingFeatures[$key])) {

                    $updateData = [];

                    for ($i = 9; $i <= 14; $i++) {
                        if (!is_null($row[$i])) {
                            $column = match ($i) {
                                9  => 'record_one',
                                10 => 'record_two',
                                11 => 'record_three',
                                12 => 'record_four',
                                13 => 'record_five',
                                14 => 'record_six',
                            };
                            $updateData[$column] = $row[$i];
                        }
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        DynamicCourseKeyfeatures::where('id', $existingFeatures[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertFeatures[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'record_one'   => $row[9] ?? null,
                        'record_two'   => $row[10] ?? null,
                        'record_three' => $row[11] ?? null,
                        'record_four'  => $row[12] ?? null,
                        'record_five'  => $row[13] ?? null,
                        'record_six'   => $row[14] ?? null,
                        'created_by'   => $userId,
                        'updated_by'   => $userId,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
            }

            /*
            ==========================================
            STEP 4: BULK INSERT
            ==========================================
            */

            if (!empty($insertPages))    DynamicCoursePages::insert($insertPages);
            if (!empty($insertSection))  DynamicCourseSectionheading::insert($insertSection);
            if (!empty($insertOverview)) CourseOverviews::insert($insertOverview);
            if (!empty($insertLearn))    Course_Whatyoulearn::insert($insertLearn);
            if (!empty($insertFeatures)) DynamicCourseKeyfeatures::insert($insertFeatures);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Import completed successfully'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Excel import error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCourseWhyChooseUs(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {

            set_time_limit(0);
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            array_shift($rows); // remove header

            $userId = Auth::id();
            $now = now();

            /*
            ==========================================
            STEP 1: BUILD UNIQUE KEYS
            ==========================================
            */

            $keyStrings = [];

            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {
                    $stateId = $row[2] ?? 'NULL';
                    $keyStrings[] = $row[0] . '-' . $row[1] . '-' . $stateId;
                }
            }

            $keyStrings = array_unique($keyStrings);

            /*
            ==========================================
            STEP 2: FETCH EXISTING RECORDS
            ==========================================
            */

            $fetchRecords = function ($model) use ($keyStrings) {
                return $model::select(
                        'id',
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                    )
                    ->whereIn(
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                        $keyStrings
                    )
                    ->get()
                    ->keyBy('key_string');
            };

            $existingSection = $fetchRecords(DynamicCourseSectionheading::class);
            $existingWhy     = $fetchRecords(CourseWhyScholarAcad::class);

            /*
            ==========================================
            STEP 3: PREPARE INSERT ARRAYS
            ==========================================
            */

            $insertSection = [];
            $insertWhy     = [];

            foreach ($rows as $row) {

                if (empty($row[0]) || empty($row[1])) continue;

                $courseId  = (int) $row[0];
                $countryId = (int) $row[1];
                $stateId   = $row[2] ?? null;

                $key = $courseId . '-' . $countryId . '-' . ($stateId ?? 'NULL');

                /*
                ==================================================
                1️⃣ DynamicCourseSectionheading (advantage)
                ==================================================
                */
                if (isset($existingSection[$key])) {

                    $updateData = [];

                    // IMPORTANT: use !is_null not !empty
                    if (!is_null($row[3])) {
                        $updateData['advantage'] = $row[3];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        DynamicCourseSectionheading::where(
                            'id',
                            $existingSection[$key]->id
                        )->update($updateData);
                    }

                } else {

                    $insertSection[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'advantage'  => $row[3] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                /*
                ==================================================
                2️⃣ CourseWhyScholarAcad
                ==================================================
                */
                if (isset($existingWhy[$key])) {

                    $updateData = [];

                    for ($i = 4; $i <= 12; $i++) {

                        if (!is_null($row[$i])) {

                            $column = match ($i) {
                                4  => 'record_one',
                                5  => 'record_two',
                                6  => 'record_three',
                                7  => 'record_four',
                                8  => 'record_five',
                                9  => 'record_six',
                                10 => 'record_seven',
                                11 => 'record_eight',
                                12 => 'record_nine',
                            };

                            $updateData[$column] = $row[$i];
                        }
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        CourseWhyScholarAcad::where(
                            'id',
                            $existingWhy[$key]->id
                        )->update($updateData);
                    }

                } else {

                    $insertWhy[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'record_one'   => $row[4] ?? null,
                        'record_two'   => $row[5] ?? null,
                        'record_three' => $row[6] ?? null,
                        'record_four'  => $row[7] ?? null,
                        'record_five'  => $row[8] ?? null,
                        'record_six'   => $row[9] ?? null,
                        'record_seven' => $row[10] ?? null,
                        'record_eight' => $row[11] ?? null,
                        'record_nine'  => $row[12] ?? null,
                        'created_by'   => $userId,
                        'updated_by'   => $userId,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
            }

            /*
            ==========================================
            STEP 4: BULK INSERT
            ==========================================
            */

            if (!empty($insertSection)) {
                DynamicCourseSectionheading::insert($insertSection);
            }

            if (!empty($insertWhy)) {
                CourseWhyScholarAcad::insert($insertWhy);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Import completed successfully'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Excel import error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCourseEligibility(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {

            set_time_limit(0);
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            array_shift($rows); // remove header

            $userId = Auth::id();
            $now    = now();

            $processed = 0;
            $skipped   = 0;

            /*
            ==========================================
            STEP 1: BUILD UNIQUE KEYS
            ==========================================
            */

            $keyStrings = [];

            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {
                    $stateId = $row[2] ?? 'NULL';
                    $keyStrings[] = $row[0] . '-' . $row[1] . '-' . $stateId;
                }
            }

            $keyStrings = array_unique($keyStrings);

            /*
            ==========================================
            STEP 2: FETCH EXISTING RECORDS
            ==========================================
            */

            $fetchRecords = function ($model) use ($keyStrings) {
                return $model::select(
                        'id',
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                    )
                    ->whereIn(
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                        $keyStrings
                    )
                    ->get()
                    ->keyBy('key_string');
            };

            $existingSection   = $fetchRecords(DynamicCourseSectionheading::class);
            $existingCert      = $fetchRecords(CertificationEligibility::class);
            $existingSalary    = $fetchRecords(CourseSalary::class);

            $insertSection = [];
            $insertCert    = [];
            $insertSalary  = [];

            /*
            ==========================================
            STEP 3: PROCESS ROWS
            ==========================================
            */

            foreach ($rows as $row) {

                $courseId  = $row[0] ?? null;
                $countryId = $row[1] ?? null;
                $stateId   = $row[2] ?? null;

                if (!$courseId || !$countryId) {
                    $skipped++;
                    continue;
                }

                $key = $courseId . '-' . $countryId . '-' . ($stateId ?? 'NULL');

                /*
                ==================================================
                1️⃣ DynamicCourseSectionheading (eligibility)
                ==================================================
                */
                if (isset($existingSection[$key])) {

                    $updateData = [];

                    if (!is_null($row[3])) {
                        $updateData['eligibility'] = $row[3];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        DynamicCourseSectionheading::where('id', $existingSection[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertSection[] = [
                        'course_id'   => $courseId,
                        'country_id'  => $countryId,
                        'state_id'    => $stateId,
                        'eligibility' => $row[3] ?? null,
                        'created_by'  => $userId,
                        'updated_by'  => $userId,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }

                /*
                ==================================================
                2️⃣ CertificationEligibility
                ==================================================
                */
                if (isset($existingCert[$key])) {

                    $updateData = [];

                    if (!is_null($row[4])) {
                        $updateData['eligibility_pointers'] = $row[4];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        CertificationEligibility::where('id', $existingCert[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertCert[] = [
                        'course_id'            => $courseId,
                        'country_id'           => $countryId,
                        'state_id'             => $stateId,
                        'eligibility_pointers' => $row[4] ?? null,
                        'created_by'           => $userId,
                        'updated_by'           => $userId,
                        'created_at'           => $now,
                        'updated_at'           => $now,
                    ];
                }

                /*
                ==================================================
                3️⃣ CourseSalary
                ==================================================
                */
                if (isset($existingSalary[$key])) {

                    $updateData = [];

                    $columns = [
                        5  => 'position_one_name',
                        6  => 'position_one_salary',
                        7  => 'position_two_name',
                        8  => 'position_two_salary',
                        9  => 'position_three_name',
                        10 => 'position_three_salary',
                    ];

                    foreach ($columns as $index => $column) {
                        if (!is_null($row[$index])) {
                            $updateData[$column] = $row[$index];
                        }
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        CourseSalary::where('id', $existingSalary[$key]->id)
                            ->update($updateData);
                    }

                } else {

                    $insertSalary[] = [
                        'course_id'             => $courseId,
                        'country_id'            => $countryId,
                        'state_id'              => $stateId,
                        'position_one_name'     => $row[5] ?? null,
                        'position_one_salary'   => $row[6] ?? null,
                        'position_two_name'     => $row[7] ?? null,
                        'position_two_salary'   => $row[8] ?? null,
                        'position_three_name'   => $row[9] ?? null,
                        'position_three_salary' => $row[10] ?? null,
                        'created_by'            => $userId,
                        'updated_by'            => $userId,
                        'created_at'            => $now,
                        'updated_at'            => $now,
                    ];
                }

                $processed++;
            }

            /*
            ==========================================
            STEP 4: BULK INSERT
            ==========================================
            */

            if (!empty($insertSection)) {
                DynamicCourseSectionheading::insert($insertSection);
            }

            if (!empty($insertCert)) {
                CertificationEligibility::insert($insertCert);
            }

            if (!empty($insertSalary)) {
                CourseSalary::insert($insertSalary);
            }

            DB::commit();

            return response()->json([
                'status'    => true,
                'message'   => 'Eligibility imported successfully',
                'processed' => $processed,
                'skipped'   => $skipped
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Eligibility Import Error: '.$e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCourseRoadmap(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {

            DB::beginTransaction();
            set_time_limit(0);

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            array_shift($rows); // remove header

            $processed = 0;
            $skipped   = 0;

            $userId = Auth::id();
            $now    = now();

            foreach ($rows as $row) {

                $courseId  = $row[0] ?? null;
                $countryId = $row[1] ?? null;
                $stateId   = $row[2] ?? null;

                if (!$courseId || !$countryId) {
                    $skipped++;
                    continue;
                }

                /*
                ==================================================
                1️⃣ DynamicCourseSectionheading
                ==================================================
                */

                $section = DynamicCourseSectionheading::where([
                    'course_id'  => $courseId,
                    'country_id' => $countryId,
                    'state_id'   => $stateId,
                ])->first();

                if ($section) {

                    $updateData = [];

                    if (!is_null($row[3])) {
                        $updateData['path'] = $row[3];
                    }

                    if (!is_null($row[4])) {
                        $updateData['trainers'] = $row[4];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        $section->update($updateData);
                    }

                } else {

                    DynamicCourseSectionheading::create([
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'path'       => $row[3] ?? null,
                        'trainers'   => $row[4] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                /*
                ==================================================
                2️⃣ CourseCertificationPath
                ==================================================
                */

                $certPath = CourseCertificationPath::where([
                    'course_id'  => $courseId,
                    'country_id' => $countryId,
                    'state_id'   => $stateId,
                ])->first();

                if ($certPath) {

                    $updateData = [];

                    $columns = [
                        5  => 'pointer_one',
                        6  => 'pointer_two',
                        7  => 'pointer_three',
                        8  => 'pointer_four',
                        9  => 'pointer_five',
                        10 => 'pointer_six',
                        11 => 'pointer_seven',
                    ];

                    foreach ($columns as $index => $column) {
                        if (!is_null($row[$index])) {
                            $updateData[$column] = $row[$index];
                        }
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        $certPath->update($updateData);
                    }

                } else {

                    CourseCertificationPath::create([
                        'course_id'    => $courseId,
                        'country_id'   => $countryId,
                        'state_id'     => $stateId,
                        'pointer_one'  => $row[5] ?? null,
                        'pointer_two'  => $row[6] ?? null,
                        'pointer_three'=> $row[7] ?? null,
                        'pointer_four' => $row[8] ?? null,
                        'pointer_five' => $row[9] ?? null,
                        'pointer_six'  => $row[10] ?? null,
                        'pointer_seven'=> $row[11] ?? null,
                        'created_by'   => $userId,
                        'updated_by'   => $userId,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ]);
                }

                $processed++;
            }

            DB::commit();

            return response()->json([
                'status'    => true,
                'message'   => 'Roadmap imported successfully',
                'processed' => $processed,
                'skipped'   => $skipped
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Roadmap Import Error: '.$e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCourseLearningObjective(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {

            set_time_limit(0);
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            array_shift($rows);

            $userId = Auth::id();
            $now = now();

            /*
            ==========================================
            BUILD KEY STRINGS
            ==========================================
            */

            $keyStrings = [];

            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {

                    $stateId = !empty($row[2]) ? trim($row[2]) : 'NULL';

                    $keyStrings[] = trim($row[0]) . '-' . trim($row[1]) . '-' . $stateId;
                }
            }

            $keyStrings = array_unique($keyStrings);

            /*
            ==========================================
            FETCH EXISTING WITH FULL DATA
            ==========================================
            */

            $existingLearn = Course_Whatyoulearn::select(
                    'id',
                    'description',
                    'pointers',
                    DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                )
                ->whereIn(
                    DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                    $keyStrings
                )
                ->get()
                ->keyBy('key_string');

            $insertLearn = [];

            foreach ($rows as $row) {

                if (empty($row[0]) || empty($row[1])) continue;

                $courseId  = (int) trim($row[0]);
                $countryId = (int) trim($row[1]);
                $stateId   = !empty($row[2]) ? (int) trim($row[2]) : null;

                $key = $courseId . '-' . $countryId . '-' . ($stateId ?? 'NULL');

                $excelDescription = isset($row[3]) ? trim($row[3]) : null;
                $excelPointersRaw = isset($row[4]) ? trim($row[4]) : null;

                $excelPointers = null;

                if (!empty($excelPointersRaw)) {
                    $decoded = json_decode($excelPointersRaw, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $excelPointers = $decoded;
                    }
                }

                /*
                ==========================================
                UPDATE EXISTING
                ==========================================
                */

                if (isset($existingLearn[$key])) {

                    $existing = $existingLearn[$key];
                    $updateData = [];

                    // ALWAYS update if Excel has value
                    if (!empty($excelDescription)) {
                        $updateData['description'] = $excelDescription;
                    }

                    if (!is_null($excelPointers)) {
                        $updateData['pointers'] = $excelPointers;
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        Course_Whatyoulearn::where('id', $existing->id)
                            ->update($updateData);
                    }

                } else {

                    $insertLearn[] = [
                        'course_id'  => $courseId,
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'description'=> !empty($excelDescription) ? $excelDescription : null,
                        'pointers'   => $excelPointers,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($insertLearn)) {
                Course_Whatyoulearn::insert($insertLearn);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Learning objective import completed successfully'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('Learning objective import error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCourseAboutGeography(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:204800',
        ]);

        try {
            set_time_limit(0);
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            array_shift($rows);

            $userId = Auth::id();
            $now = now();

            /*
            ==========================================
            STEP 1: BUILD UNIQUE KEY STRINGS
            ==========================================
            */

            $keyStrings = [];
            $geoKeys = [];

            foreach ($rows as $row) {
                if (!empty($row[0]) && !empty($row[1])) {
                    $stateId = $row[2] ?? 'NULL';
                    $keyStrings[] = $row[0] . '-' . $row[1] . '-' . $stateId;

                    // AboutGeography uses country-state only
                    $geoKey = $row[1] . '-' . $stateId;
                    $geoKeys[] = $geoKey;
                }
            }

            $keyStrings = array_unique($keyStrings);
            $geoKeys = array_unique($geoKeys);

            /*
            ==========================================
            STEP 2: FETCH EXISTING RECORDS
            ==========================================
            */

            $fetchCourseRecords = function ($model) use ($keyStrings) {
                return $model::select(
                        'id',
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                    )
                    ->whereIn(
                        DB::raw("CONCAT(course_id,'-',country_id,'-',IFNULL(state_id,'NULL'))"),
                        $keyStrings
                    )
                    ->get()
                    ->keyBy('key_string');
            };

            $fetchGeoRecords = function ($model) use ($geoKeys) {
                return $model::select(
                        'id',
                        DB::raw("CONCAT(country_id,'-',IFNULL(state_id,'NULL')) as key_string")
                    )
                    ->whereIn(
                        DB::raw("CONCAT(country_id,'-',IFNULL(state_id,'NULL'))"),
                        $geoKeys
                    )
                    ->get()
                    ->keyBy('key_string');
            };

            $existingSection = $fetchCourseRecords(DynamicCourseSectionheading::class);
            $existingGeography = $fetchGeoRecords(AboutGeography::class);

            /*
            ==========================================
            STEP 3: PREPARE INSERT ARRAYS
            ==========================================
            */

            $insertSection = [];
            $insertGeography = [];

            foreach ($rows as $row) {
                if (empty($row[0]) || empty($row[1])) continue;

                $courseId  = (int) $row[0];
                $countryId = (int) $row[1];
                $stateId   = $row[2] ?? null;

                $courseKey = $courseId . '-' . $countryId . '-' . ($stateId ?? 'NULL');
                $geoKey = $countryId . '-' . ($stateId ?? 'NULL');

                /*
                ==================================================
                1️⃣ DynamicCourseSectionheading (course-country-state)
                ==================================================
                */
                if (isset($existingSection[$courseKey])) {
                    // UPDATE ONLY NON-NULL FIELDS
                    $updateData = [];

                    if (!is_null($row[3])) $updateData['about_geography'] = $row[3];
                    if (!is_null($row[4])) $updateData['hiring_company'] = $row[4];
                    if (!is_null($row[5])) $updateData['alumni'] = $row[5];
                    if (!is_null($row[6])) $updateData['segment'] = $row[6];
                    if (!is_null($row[7])) $updateData['recommended_course'] = $row[7];
                    if (!is_null($row[8])) $updateData['roc_option1'] = $row[8];
                    if (!is_null($row[9])) $updateData['roc_option2'] = $row[9];
                    if (!is_null($row[10])) $updateData['roc_option3'] = $row[10];
                    if (!is_null($row[11])) $updateData['roc_option4'] = $row[11];

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        DynamicCourseSectionheading::where('id', $existingSection[$courseKey]->id)
                            ->update($updateData);
                    }
                    // Empty Excel cells = existing DB data preserved ✅

                } else {
                    $insertSection[] = [
                        'course_id'         => $courseId,
                        'country_id'        => $countryId,
                        'state_id'          => $stateId,
                        'about_geography'   => $row[3] ?? null,
                        'hiring_company'    => $row[4] ?? null,
                        'alumni'            => $row[5] ?? null,
                        'segment'           => $row[6] ?? null,
                        'recommended_course'=> $row[7] ?? null,
                        'roc_option1'       => $row[8] ?? null,
                        'roc_option2'       => $row[9] ?? null,
                        'roc_option3'       => $row[10] ?? null,
                        'roc_option4'       => $row[11] ?? null,
                        'created_by'        => $userId,
                        'updated_by'        => $userId,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];
                }

                /*
                ==================================================
                2️⃣ AboutGeography (country-state only)
                ==================================================
                */
                if (isset($existingGeography[$geoKey])) {
                    // UPDATE ONLY NON-NULL FIELDS
                    $updateData = [];

                    if (!is_null($row[12])) {
                        $updateData['description'] = $row[12];
                    }

                    if (!empty($updateData)) {
                        $updateData['updated_by'] = $userId;
                        $updateData['updated_at'] = $now;

                        AboutGeography::where('id', $existingGeography[$geoKey]->id)
                            ->update($updateData);
                    }
                    // Empty Excel cell = existing DB description preserved ✅

                } else {
                    $insertGeography[] = [
                        'country_id' => $countryId,
                        'state_id'   => $stateId,
                        'description'=> $row[12] ?? null,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            /*
            ==========================================
            STEP 4: BULK INSERT
            ==========================================
            */

            if (!empty($insertSection)) {
                DynamicCourseSectionheading::insert($insertSection);
            }
            if (!empty($insertGeography)) {
                AboutGeography::insert($insertGeography);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'About Geography imported successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('About Geography import error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // public function updateCourseCurriculum(Request $request)
  // {
    //     $request->validate([
    //         'excel_file' => 'required|mimes:xlsx,xls,csv',
    //     ]);

    //     try {
    //         File::setUseUploadTempDirectory(true);

    //         $file = $request->file('excel_file');
    //         $spreadsheet = IOFactory::load($file->getPathname());
    //         $rows = $spreadsheet->getActiveSheet()->toArray();

    //         unset($rows[0]); // remove header

    //         $processed = 0;
    //         $skipped   = 0;

    //         foreach ($rows as $row) {

    //             $courseId  = $row[0] ?? null;
    //             $countryId = $row[1] ?? null;
    //             $stateId   = $row[2] ?? null;

    //             if (!$courseId || !$countryId || !$stateId) {
    //                 $skipped++;
    //                 continue;
    //             }


    //             /* =========================
    //             DynamicCourseSectionheading
    //             ========================== */
    //             DynamicCourseSectionheading::updateOrCreate(
    //                 [
    //                     'course_id'  => $courseId,
    //                     'country_id' => $countryId,
    //                     'state_id'   => $stateId,
    //                 ],
    //                 [
    //                     'Curriculummmm'        => $row[3] ?? null,
    //                     'trainers'    => $row[4] ?? null,
    //                     'created_at'              => Carbon::now(),
    //                     'updated_by'              => Auth::id(),
    //                 ]
    //             );

    //             /* =========================
    //             eligiblity
    //             ========================== */
    //             CourseCurriculumFaq::updateOrCreate(
    //                 [
    //                     'course_id'  => $courseId,
    //                 ],
    //                 [
    //                     'title'   => $row[5]  ?? null,
    //                     'description'   => $row[6]  ?? null,
    //                     'created_at'   => Carbon::now(),
    //                     'updated_by'   => Auth::id(),
    //                 ]
    //             );

    //             $processed++;
    //         }

    //         return response()->json([
    //             'status'    => true,
    //             'message'   => 'roadmap imported successfully',
    //             'processed' => $processed,
    //             'skipped'   => $skipped
    //         ]);

    //     } catch (\Throwable $e) {
    //         Log::error('roadmap Import Error: '.$e->getMessage());

    //         return response()->json([
    //             'status'  => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function downloadFromFormateExcel()
    // {
    //     $filePath = public_path('templates/course_import_template.xlsx');

    //     abort_if(!file_exists($filePath), 404, 'Template file not found');

    //     return response()->download(
    //         $filePath,
    //         'course_import_template.xlsx',
    //         [
    //             'Content-Type' =>
    //                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    //         ]
    //     );
  // }

    public function downloadFromFormateExcel(Request $request)
    {
        $type = $request->type;

        $allowedTypes = ['pointer', 'roadmap', 'highlights' , 'state' ,'learningObjective' , 'faqs','aboutGeography' , 'whychooseus','eligibility'];

        if (!in_array($type, $allowedTypes)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid template type'
            ], 404);
        }

        $fileName = "course_{$type}_template.xlsx";

        if($type == 'faqs'){
            $fileName = "{$type}_template.xlsx";
        }
        $filePath = public_path("templates/{$fileName}");

        if (!file_exists($filePath)) {
            return response()->json([
                'status' => false,
                'message' => 'Template not found'
            ], 404);
        }

        return response()->download($filePath, $fileName);
    }




    public function updateFAQS(Request $request)
    {
        $request->validate(['excel_file' => 'required|mimes:xlsx,xls,csv']);

        try {
            File::setUseUploadTempDirectory(true);
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();
            unset($rows[0]);

            $created = 0;
            $updated = 0;

            // ✅ FIX 1: Use ::class instead of Controller::model()
            $FaqModelClass = Controller::model('FaqsGeneral', 'FAQS');

            foreach ($rows as $row) {
                if (empty(array_filter($row))) continue;

                $excelTitle = trim($row[2] ?? '');
                if (empty($excelTitle)) continue;
                // dd($excelTitle);

                // Your course/category lookup (unchanged)
                $countryId = null;
                $countryName = trim($row[0] ?? '');
                if ($countryName) {
                    $country = Course::where('course_title', $countryName)->first();
                    $countryId = $country?->id;
                }

                $categoryId = null;
                $categoryName = trim($row[1] ?? '');
                if ($categoryName) {
                    $category = FAQS_Category::where('category_name', $categoryName)->first();
                    $categoryId = $category?->id;
                }

                $data = [
                    'course_id'     => $countryId,
                    'category_id'   => $categoryId,
                    'title'         => $excelTitle,
                    'description'   => $row[3] ?? null,
                    'company_id'    => Auth::user()->company_id,
                    'updated_by'    => Auth::id(),
                ];

                // ✅ FIX 2: Use CLASSNAME::query() or new CLASSNAME()
                $FaqModel = $FaqModelClass; // String class name

                // Title-based UPSERT
                $existingFaq = $FaqModel::where('title', $excelTitle)
                                    ->where('company_id', Auth::user()->company_id)
                                    ->first();

                if ($existingFaq) {
                    $existingFaq->update($data);
                    $updated++;
                } else {
                    $data['created_by'] = Auth::id();
                    $FaqModel::create($data);
                    $created++;
                }
            }

            return response()->json([
                'status' => true,
                'message' => "FAQs imported! Created: {$created}, Updated: {$updated}"
            ]);

        } catch (\Throwable $e) {
            Log::error('FAQ Excel import error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function downloadFAQSFormateExcel(Request $request)
    {
        try {
            $filePath = public_path('templates/faqs_import_template.xlsx');

            // Check if file exists
            if (!file_exists($filePath)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Template not found at: public/templates/faqs_import_template.xlsx'
                ], 404);
            }

            // ✅ Download with correct MIME type
            return Response::download(
                $filePath,
                'faqs_import_template.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]
            );

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateState(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            File::setUseUploadTempDirectory(true);

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            // Remove header
            unset($rows[0]);

            $created = 0;
            $updated = 0;

            foreach ($rows as $row) {

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // ✅ REQUIRED: State Name
                $stateName = trim($row[0] ?? '');
                if (!$stateName) {
                    continue;
                }

                // Resolve country
                $countryId = null;

                $countryName = trim($row[3] ?? null);
                if ($countryName) {
                    $country = CourseCountry::where('name', $countryName)->first();
                    if ($country) {
                        $countryId = $country->id;
                    }
                }

                // Prepare data
                $data = [
                    'name'         => $stateName,
                    'url'          => $this->excelNull($row[1] ?? null),
                    'isPage'       => in_array($row[2], ['Yes','No']) ? $row[2] : 'No',
                    'country_id'   => $countryId,
                    'country_code' => $this->excelNull($row[4] ?? null),
                    'fips_code'    => $this->excelNull($row[5] ?? null),
                    'iso2'         => $this->excelNull($row[6] ?? null),
                    'type'         => $this->excelNull($row[7] ?? null),
                    'latitude'     => is_numeric($row[8]) ? $row[8] : null,
                    'longitude'    => is_numeric($row[9]) ? $row[9] : null,
                    'flag'         => is_numeric($row[10]) ? $row[10] : 1,
                    'status'       => in_array($row[11], ['Active','Inactive']) ? $row[11] : 'Active',

                    'company_id'   => Auth::user()->company_id,
                    'updated_by'   => Auth::id(),
                    'updated_at'   => now(),
                ];

                // 🔎 CHECK EXISTING STATE (NAME + COMPANY)
                $existingState = State::where('name', $stateName)
                    ->where('company_id', Auth::user()->company_id)
                    ->first();

                if ($existingState) {
                    // UPDATE
                    $existingState->update($data);
                    $updated++;
                } else {
                    // CREATE
                    $data['created_by'] = Auth::id();
                    $data['created_at'] = now();

                    State::create($data);
                    $created++;
                }
            }

            return response()->json([
                'status'  => true,
                'message' => "State import completed. Created: {$created}, Updated: {$updated}"
            ]);

        } catch (\Throwable $e) {
            Log::error('State Excel Import Error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadStateFormateExcel(Request $request)
    {
        try {
            $filePath = public_path('templates/state_template.xlsx');

            // Check if file exists
            if (!file_exists($filePath)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Template not found at: public/templates/state_template.xlsx'
                ], 404);
            }

            // ✅ Download with correct MIME type
            return Response::download(
                $filePath,
                'state_template.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]
            );

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function downloadPointerFormateCsv(Request $request)
    // {
    //     try {

    //         set_time_limit(0);

    //         $fileName = "course_headline_export.csv";

    //         return response()->stream(function () {

    //             $handle = fopen('php://output', 'w');

    //             // Header row
    //             fputcsv($handle, [
    //                 'course_id',
    //                 'country_id',
    //                 'state_id',
    //                 'course_description',
    //                 'course_highlights_in_pointers',
    //                 'hone_tag',
    //                 'meta_title',
    //                 'meta_keywords',
    //                 'imageAltText',
    //                 'metaDescription'
    //             ]);

    //             DB::table('tbl_dynamic_course_headline as d')
    //                 ->leftJoin('tbl_course_highlights as h', function ($join) {
    //                     $join->on('d.course_id', '=', 'h.course_id')
    //                         ->on('d.country_id', '=', 'h.country_id')
    //                         ->whereRaw('(d.state_id <=> h.state_id)');
    //                 })
    //                 ->select(
    //                     'd.course_id',
    //                     'd.country_id',
    //                     'd.state_id',
    //                     'h.course_description',
    //                     'h.course_highlights_in_pointers',
    //                     'd.hone_tag',
    //                     'd.meta_title',
    //                     'd.meta_keywords',
    //                     'd.imageAltText',
    //                     'd.metaDescription'
    //                 )
    //                 ->orderBy('d.course_id')
    //                 ->cursor()
    //                 ->each(function ($record) use ($handle) {

    //                     fputcsv($handle, [
    //                         $record->course_id,
    //                         $record->country_id,
    //                         $record->state_id,
    //                         $record->course_description,
    //                         $record->course_highlights_in_pointers,
    //                         $record->hone_tag,
    //                         $record->meta_title,
    //                         $record->meta_keywords,
    //                         $record->imageAltText,
    //                         $record->metaDescription
    //                     ]);
    //                 });

    //             fclose($handle);

    //         }, 200, [
    //             "Content-Type" => "text/csv",
    //             "Content-Disposition" => "attachment; filename={$fileName}",
    //         ]);

    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Download failed: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function downloadPointerFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);

            $fileName = "course_headline_export_" . now()->timestamp . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'course_description',
                    'course_highlights_in_pointers',
                    'hone_tag',
                    'meta_title',
                    'meta_keywords',
                    'imageAltText',
                    'metaDescription',
                    'courseName',
                    'Country',
                    'State',
                ]);

                $query = DB::table('tbl_dynamic_course_headline as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')

                    ->leftJoin('tbl_course_highlights as h', function ($join) {
                        $join->on('d.course_id', '=', 'h.course_id')
                            ->on('d.country_id', '=', 'h.country_id')
                            ->whereRaw('d.state_id <=> h.state_id');
                    })

                    ->select([
                        'd.id as id',
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'h.course_description',
                        'h.course_highlights_in_pointers',
                        'd.hone_tag',
                        'd.meta_title',
                        'd.meta_keywords',
                        'd.imageAltText',
                        'd.metaDescription',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name',
                    ])

                    ->orderBy('d.id');

                $query->chunkById(
                    1000,
                    function ($records) use ($handle) {

                        foreach ($records as $record) {

                            fputcsv($handle, [
                                $record->course_id,
                                $record->country_id,
                                $record->state_id,
                                $record->course_description,
                                $record->course_highlights_in_pointers,
                                $record->hone_tag,
                                $record->meta_title,
                                $record->meta_keywords,
                                $record->imageAltText,
                                $record->metaDescription,
                                $record->courseName,
                                $record->country_name,
                                $record->state_name,
                            ]);
                        }

                        flush(); // improves streaming speed
                    },
                    'd.id',
                    'id'
                );

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }


//     public function downloadPointerFormateCsv(Request $request)
// {
//     set_time_limit(0);

//     $fileName = "course_headline_export_" . date('Ymd_His') . ".csv";

//     return response()->streamDownload(function () {

//         $handle = fopen('php://output', 'w');

//         // CSV Header
//         fputcsv($handle, [
//             'course_id',
//             'country_id',
//             'state_id',
//             'course_description',
//             'course_highlights_in_pointers',
//             'hone_tag',
//             'meta_title',
//             'meta_keywords',
//             'imageAltText',
//             'metaDescription'
//         ]);

//         DB::table('tbl_dynamic_course_headline as d')
//             ->leftJoin('tbl_course_highlights as h', function ($join) {
//                 $join->on('d.course_id', '=', 'h.course_id')
//                      ->on('d.country_id', '=', 'h.country_id')
//                      ->whereRaw('d.state_id <=> h.state_id');
//             })
//             ->select(
//                 'd.course_id',
//                 'd.country_id',
//                 'd.state_id',
//                 'h.course_description',
//                 'h.course_highlights_in_pointers',
//                 'd.hone_tag',
//                 'd.meta_title',
//                 'd.meta_keywords',
//                 'd.imageAltText',
//                 'd.metaDescription'
//             )
//             ->orderBy('d.course_id')
//             ->chunk(1000, function ($records) use ($handle) {

//                 foreach ($records as $record) {
//                     fputcsv($handle, (array) $record);
//                 }

//                 fflush($handle);
//             });

//         fclose($handle);

//     }, $fileName, [
//         "Content-Type" => "text/csv",
//         "Cache-Control" => "no-store, no-cache",
//     ]);
// }








private function excelNull($value)
{
    if ($value === 'NULL' || $value === '' || $value === null) {
        return null;
    }
    return $value;
}


    public function downloadHighlightsFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);

            $fileName = "course_highlights_export_" . now()->timestamp . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'program_overview',
                    'training_highlights',
                    'new_learning_objectives',
                    'course_overview',
                    'description',
                    'pointers',
                    'record_one',
                    'record_two',
                    'record_three',
                    'record_four',
                    'record_five',
                    'record_six',
                    'courseName',
                    'Country',
                    'State',
                ]);

                $query = DB::table('tbl_dynamic_course_sectionheading as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')

                    ->leftJoin('tbl_course_highlights as h', function ($join) {
                        $join->on('d.course_id', '=', 'h.course_id')
                            ->on('d.country_id', '=', 'h.country_id')
                            ->whereRaw('d.state_id <=> h.state_id');
                    })

                    ->leftJoin('tbl_course_what_you_will_learn as w', function ($join) {
                        $join->on('d.course_id', '=', 'w.course_id')
                            ->on('d.country_id', '=', 'w.country_id')
                            ->whereRaw('d.state_id <=> w.state_id');
                    })

                    ->leftJoin('tbl_coursekey_features as f', function ($join) {
                        $join->on('d.course_id', '=', 'f.course_id')
                            ->on('d.country_id', '=', 'f.country_id')
                            ->whereRaw('d.state_id <=> f.state_id');
                    })

                    ->leftJoin('tbl_course_overview as o', function ($join) {
                        $join->on('d.course_id', '=', 'o.course_id')
                            ->on('d.country_id', '=', 'o.country_id')
                            ->whereRaw('d.state_id <=> o.state_id');
                    })

                    ->select([
                        'd.id as id',   // required for chunkById
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'd.program_overview',
                        'd.training_highlights',
                        'd.new_learning_objectives',
                        'o.course_overview',
                        'w.description',
                        'w.pointers',
                        'f.record_one',
                        'f.record_two',
                        'f.record_three',
                        'f.record_four',
                        'f.record_five',
                        'f.record_six',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name'
                    ])
                    ->orderBy('d.id');

                $query->chunkById(
                    1000,
                    function ($records) use ($handle) {

                        foreach ($records as $record) {

                            fputcsv($handle, [
                                $record->course_id,
                                $record->country_id,
                                $record->state_id,
                                $record->program_overview,
                                $record->training_highlights,
                                $record->new_learning_objectives,
                                $record->course_overview,
                                $record->description,
                                $record->pointers,
                                $record->record_one,
                                $record->record_two,
                                $record->record_three,
                                $record->record_four,
                                $record->record_five,
                                $record->record_six,
                                $record->courseName,
                                $record->country_name,
                                $record->state_name,
                            ]);
                        }

                        flush();

                    },
                    'd.id', // actual DB column
                    'id'    // alias
                );

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadWhyChooseUsFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);

            $fileName = "course_whyChooseUs_export_" . now()->timestamp . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'advantage',
                    'record_one',
                    'record_two',
                    'record_three',
                    'record_four',
                    'record_five',
                    'record_six',
                    'record_seven',
                    'record_eight',
                    'record_nine',
                    'courseName',
                    'Country',
                    'State',
                ]);

                $query = DB::table('tbl_dynamic_course_sectionheading as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')

                    ->leftJoin('tbl_course_why_scholaracad as f', function ($join) {
                        $join->on('d.course_id', '=', 'f.course_id')
                            ->on('d.country_id', '=', 'f.country_id')
                            ->whereRaw('d.state_id <=> f.state_id');
                    })

                    ->select([
                        'd.id as id',
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'd.advantage',
                        'f.record_one',
                        'f.record_two',
                        'f.record_three',
                        'f.record_four',
                        'f.record_five',
                        'f.record_six',
                        'f.record_seven',
                        'f.record_eight',
                        'f.record_nine',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name'
                    ])
                    ->orderBy('d.id');

                $query->chunkById(

                    1000,

                    function ($records) use ($handle) {

                        foreach ($records as $record) {

                            fputcsv($handle, [
                                $record->course_id,
                                $record->country_id,
                                $record->state_id,
                                $record->advantage,
                                $record->record_one,
                                $record->record_two,
                                $record->record_three,
                                $record->record_four,
                                $record->record_five,
                                $record->record_six,
                                $record->record_seven,
                                $record->record_eight,
                                $record->record_nine,
                                $record->courseName,
                                $record->country_name,
                                $record->state_name,
                            ]);
                        }

                        flush();

                    },

                    'd.id',
                    'id'
                );

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadEligiblityFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);

            $fileName = "course_Eligiblity_export_" . now()->timestamp . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'eligibility',
                    'eligibility_pointers',
                    'position_one_name',
                    'position_one_salary',
                    'position_two_name',
                    'position_two_salary',
                    'position_three_name',
                    'position_three_salary',
                    'courseName',
                    'Country',
                    'State',
                ]);

                $query = DB::table('tbl_dynamic_course_sectionheading as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')

                    ->leftJoin('tbl_certification_eligibility as k', function ($join) {
                        $join->on('d.course_id', '=', 'k.course_id')
                            ->on('d.country_id', '=', 'k.country_id')
                            ->whereRaw('d.state_id <=> k.state_id');
                    })

                    ->leftJoin('tbl_dynamic_course_salary as f', function ($join) {
                        $join->on('d.course_id', '=', 'f.course_id')
                            ->on('d.country_id', '=', 'f.country_id')
                            ->whereRaw('d.state_id <=> f.state_id');
                    })

                    ->select([
                        'd.id as id', // REQUIRED for chunkById
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'd.eligibility',
                        'k.eligibility_pointers',
                        'f.position_one_name',
                        'f.position_one_salary',
                        'f.position_two_name',
                        'f.position_two_salary',
                        'f.position_three_name',
                        'f.position_three_salary',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name'
                    ])
                    ->orderBy('d.id');

                $query->chunkById(
                    1000,
                    function ($records) use ($handle) {

                        foreach ($records as $record) {

                            fputcsv($handle, [
                                $record->course_id,
                                $record->country_id,
                                $record->state_id,
                                $record->eligibility,
                                $record->eligibility_pointers,
                                $record->position_one_name,
                                $record->position_one_salary,
                                $record->position_two_name,
                                $record->position_two_salary,
                                $record->position_three_name,
                                $record->position_three_salary,
                                $record->courseName,
                                $record->country_name,
                                $record->state_name,
                            ]);
                        }

                        flush(); // improves streaming speed

                    },
                    'd.id',  // actual DB column
                    'id'     // alias used in select
                );

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }


    public function downloadRoadmapFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);

            $fileName = "course_roadmap_export_" . time() . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'path',
                    'trainers',
                    'pointer_one',
                    'pointer_two',
                    'pointer_three',
                    'pointer_four',
                    'pointer_five',
                    'pointer_six',
                    'pointer_seven',
                    'courseName',
                    'Country',
                    'State',
                ]);

                DB::table('tbl_dynamic_course_sectionheading as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')
                    ->leftJoin('tbl_certification_path as h', function ($join) {
                        $join->on('d.course_id', '=', 'h.course_id')
                            ->on('d.country_id', '=', 'h.country_id')
                            ->whereRaw('d.state_id <=> h.state_id');
                    })
                    ->select(
                        'd.id as id',
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'd.path',
                        'd.trainers',
                        'h.pointer_one',
                        'h.pointer_two',
                        'h.pointer_three',
                        'h.pointer_four',
                        'h.pointer_five',
                        'h.pointer_six',
                        'h.pointer_seven',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name'
                    )
                    ->chunkById(1000, function ($records) use ($handle) {

                        foreach ($records as $record) {

                            fputcsv($handle, [
                                $record->course_id,
                                $record->country_id,
                                $record->state_id,
                                $record->path,
                                $record->trainers,
                                $record->pointer_one,
                                $record->pointer_two,
                                $record->pointer_three,
                                $record->pointer_four,
                                $record->pointer_five,
                                $record->pointer_six,
                                $record->pointer_seven,
                                $record->courseName,
                                $record->country_name,
                                $record->state_name,
                            ]);
                        }

                        flush();
                    }, 'd.id',
                         'id');

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function downloadLearningObjectiveFormateCsv(Request $request)
    // {
    //     try {

    //         set_time_limit(0);

    //         $fileName = "course_learning_objective_export_" . now()->timestamp . ".csv";

    //         return response()->streamDownload(function () {

    //             $handle = fopen('php://output', 'w');

    //             // CSV Header
    //             fputcsv($handle, [
    //                 'course_id',
    //                 'country_id',
    //                 'state_id',
    //                 'course_benefits',
    //                 'certifications',
    //                 'why_course_important_readmore',
    //                 'record_one',
    //                 'record_two',
    //                 'record_three',
    //                 'record_four',
    //                 'record_five',
    //                 'record_six',
    //                 'description',
    //                 'courseName',
    //                 'Country',
    //                 'State',
    //             ]);

    //             $query = DB::table('tbl_dynamic_course_sectionheading as d')
    //                 ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
    //                 ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
    //                 ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')
    //                 ->leftJoin('tbl_course_benefits as h', function ($join) {
    //                     $join->on('d.course_id', '=', 'h.course_id')
    //                         ->on('d.country_id', '=', 'h.country_id')
    //                         ->whereRaw('d.state_id <=> h.state_id');
    //                 })
    //                 ->leftJoin('tbl_course_learning_objectives as w', function ($join) {
    //                     $join->on('d.course_id', '=', 'w.course_id')
    //                         ->on('d.country_id', '=', 'w.country_id')
    //                         ->whereRaw('d.state_id <=> w.state_id');
    //                 })
    //                 ->leftJoin('tbl_certificate_text as f', function ($join) {
    //                     $join->on('d.course_id', '=', 'f.course_id')
    //                         ->on('d.country_id', '=', 'f.country_id')
    //                         ->whereRaw('d.state_id <=> f.state_id');
    //                 })
    //                 ->select([
    //                     'd.id as id',
    //                     'd.course_id',
    //                     'd.country_id',
    //                     'd.state_id',
    //                     'd.course_benefits',
    //                     'd.certifications',
    //                     'h.why_course_important_readmore',
    //                     'w.record_one',
    //                     'w.record_two',
    //                     'w.record_three',
    //                     'w.record_four',
    //                     'w.record_five',
    //                     'w.record_six',
    //                     'f.description',
    //                     'j.course_title as courseName',
    //                     'c.name as country_name',
    //                     's.name as state_name'
    //                 ])
    //                 ->orderBy('id');

    //             // Best for large tables
    //             $query->chunkById(1000, function ($records) use ($handle) {

    //                 foreach ($records as $record) {

    //                     fputcsv($handle, [
    //                         $record->course_id,
    //                         $record->country_id,
    //                         $record->state_id,
    //                         $record->course_benefits,
    //                         $record->certifications,
    //                         $record->why_course_important_readmore,
    //                         $record->record_one,
    //                         $record->record_two,
    //                         $record->record_three,
    //                         $record->record_four,
    //                         $record->record_five,
    //                         $record->record_six,
    //                         $record->description,
    //                         $record->courseName,
    //                         $record->country_name,
    //                         $record->state_name,
    //                     ]);
    //                 }

    //                 flush(); // Important for faster streaming

    //             }, 'd.id',
    //                 'id'   );

    //             fclose($handle);

    //         }, $fileName, [
    //             "Content-Type" => "text/csv",
    //         ]);

    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Download failed: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function downloadLearningObjectiveFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);
            DB::disableQueryLog(); // VERY IMPORTANT

            $fileName = "course_learning_objective_export_" . now()->timestamp . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'course_benefits',
                    'certifications',
                    'why_course_important_readmore',
                    'record_one',
                    'record_two',
                    'record_three',
                    'record_four',
                    'record_five',
                    'record_six',
                    'description',
                    'courseName',
                    'Country',
                    'State',
                ]);

                DB::table('tbl_dynamic_course_sectionheading as d')

                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')

                    ->leftJoin('tbl_course_benefits as h', function ($join) {
                        $join->on('d.course_id', '=', 'h.course_id')
                            ->on('d.country_id', '=', 'h.country_id')
                            ->whereColumn('d.state_id', 'h.state_id');
                    })

                    ->leftJoin('tbl_course_learning_objectives as w', function ($join) {
                        $join->on('d.course_id', '=', 'w.course_id')
                            ->on('d.country_id', '=', 'w.country_id')
                            ->whereColumn('d.state_id', 'w.state_id');
                    })

                    ->leftJoin('tbl_certificate_text as f', function ($join) {
                        $join->on('d.course_id', '=', 'f.course_id')
                            ->on('d.country_id', '=', 'f.country_id')
                            ->whereColumn('d.state_id', 'f.state_id');
                    })

                    ->select([
                        'd.id as id',
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'd.course_benefits',
                        'd.certifications',
                        'h.why_course_important_readmore',
                        'w.record_one',
                        'w.record_two',
                        'w.record_three',
                        'w.record_four',
                        'w.record_five',
                        'w.record_six',
                        'f.description',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name'
                    ])

                    ->chunkById(5000, function ($rows) use ($handle) {

                        foreach ($rows as $record) {

                            fputcsv($handle, [
                                $record->course_id,
                                $record->country_id,
                                $record->state_id,
                                $record->course_benefits,
                                $record->certifications,
                                $record->why_course_important_readmore,
                                $record->record_one,
                                $record->record_two,
                                $record->record_three,
                                $record->record_four,
                                $record->record_five,
                                $record->record_six,
                                $record->description,
                                $record->courseName,
                                $record->country_name,
                                $record->state_name,
                            ]);
                        }

                        flush();

                    }, 'd.id' ,
                        'id');

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadAboutGeographyFormateCsv(Request $request)
    {
        try {

            set_time_limit(0);

            $fileName = "course_about_geography_export_" . now()->timestamp . ".csv";

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'course_id',
                    'country_id',
                    'state_id',
                    'about_geography',
                    'hiring_company',
                    'alumni',
                    'segment',
                    'recommended_course',
                    'roc_option1',
                    'roc_option2',
                    'roc_option3',
                    'roc_option4',
                    'description',
                    'courseName',
                    'Country',
                    'State',
                ]);

                $query = DB::table('tbl_dynamic_course_sectionheading as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as j', 'd.course_id', '=', 'j.id')
                    ->leftJoin(DB::raw("
                            (SELECT country_id, state_id, MAX(description) as description
                            FROM tbl_about_geography
                            GROUP BY country_id, state_id
                            ) as h
                        "), function ($join) {
                            $join->on('d.country_id', '=', 'h.country_id')
                                ->whereRaw('d.state_id <=> h.state_id');
                        })
                    ->select([
                        'd.id as id',
                        'd.course_id',
                        'd.country_id',
                        'd.state_id',
                        'd.about_geography',
                        'd.hiring_company',
                        'd.alumni',
                        'd.segment',
                        'd.recommended_course',
                        'd.roc_option1',
                        'd.roc_option2',
                        'd.roc_option3',
                        'd.roc_option4',
                        'h.description',
                        'j.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name'
                    ])
                    ->orderBy('id');

                // Best for large tables
                $query->chunkById(1000, function ($records) use ($handle) {

                    foreach ($records as $record) {

                        fputcsv($handle, [
                            $record->course_id,
                            $record->country_id,
                            $record->state_id,
                            $record->about_geography,
                            $record->hiring_company,
                            $record->alumni,
                            $record->segment,
                            $record->recommended_course,
                            $record->roc_option1,
                            $record->roc_option2,
                            $record->roc_option3,
                            $record->roc_option4,
                            $record->description,
                            $record->courseName,
                            $record->country_name,
                            $record->state_name,
                        ]);
                    }

                    flush(); // Important for faster streaming

                }, 'd.id',
                    'id'   );

                fclose($handle);

            }, $fileName, [
                "Content-Type" => "text/csv",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }



}

