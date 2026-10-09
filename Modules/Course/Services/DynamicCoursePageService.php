<?php

namespace Modules\Course\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Repositories\DynamicCoursePagesRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class DynamicCoursePageService extends Controller
{
    protected DynamicCoursePagesRepository $repository;

    public function __construct(DynamicCoursePagesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function DynamicCoursePagesList(Request $request)
    {
        $companyId = Controller::loginandcompany();
        $courseTitles = Controller::model('Course', 'Course')::orderby('id', 'DESC')->get();
        $countries = Controller::model('CourseCountry', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
        $states = Controller::model('State', 'Course')::where('company_id', $companyId[1])->orderby('id', 'DESC')->get();
        try {
            if ($request->ajax()) {

                $query = $this->repository->DynamicCoursePagesList();
                $query = $this->repository->DynamicCourseFilters($query, $request)->orderBy('id', 'desc');
                // Filter by courseDate
                 if ($request->filled('search.value')) {
                    $search = $request->search['value'];
                    $query->whereRelation('course', 'course_title', 'like', "%{$search}%")
                    ->orWhereRelation('CourseCountryName', 'name', 'like', "%{$search}%")
                    ->orWhereRelation('stateName', 'name', 'like', "%{$search}%");
                }
                // Filter by course by Name
                if ($request->courseDate) {
                    $query->whereRelation('courseDate','course_title', 'like',"%{$request->courseDate}%");
                }

                // Filter by CourseCountryName
                if ($request->CourseCountryName) {
                     $query->whereRelation('CourseCountryName','name','like',"%{$request->CourseCountryName}%");
                }

                // Filter by stateName
                if ($request->stateName) {
                      $query->whereRelation('stateName','name','like',"%{$request->stateName}%");
                }

                return DataTables::of($query)
                    ->addColumn('Coursedate', function ($row) {
                        return $row?->Coursedate?->course_title;
                    })
                    ->addColumn('CourseCountryName', function ($row) {
                        return $row?->CourseCountryName?->name;
                    })
                    ->addColumn('CourseCountryISO2', function ($row) {
                        return $row?->CourseCountryName?->iso2;
                    })
                    ->addColumn('state', function ($row) {
                        return $row?->stateName?->name;
                    })
                    ->addColumn('status', function ($row) {
                        return $row?->is_ready;
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator->name;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })

                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        //  $view_url = route('dynamic-course-pages-view', ['id' => $incr]);
                        $edit_url = route('dynamic-course-pages-edit', ['id' => $incr]);
                        $delete_url = route('dynamic-course-pages-destroy', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';


                        $dropdown .= '<li><a href="'.$edit_url.'" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        $dropdown .= '<li>
                                <a href="javascript:void(0);"
                                onclick="deleteRecord(\''.$delete_url.'\')"
                                class="block px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </a>
                            </li>';
                        // $dropdown .= '<li><a href="#" onclick="openNewModal(\'publish-course\')" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-rocket-line mr-2"></i>Publish</a></li>';


                        $dropdown .= '</ul></div></div>';

                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'Coursedate','CourseCountryISO2' , 'CourseCountryName', 'state', 'status', 'created_by', 'created_at'])
                    ->make(true);
            }

            return view('Course::Dynamic_pages.index', compact('courseTitles', 'countries', 'states'));
        } catch (\Exception $e) {
            catch_log($e, 'Getting list of Course Schude data');
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }

            return back()->with('error', $e->getMessage());
        }
    }

   public function DynamicCoursePagesCreate($id = null, $view = null)
    {

        try {
            $data = $this->repository->DynamicCoursePagesCreate($id, $view);

            return $data;
        } catch (\Exception $e) {
            catch_log($e, 'Viewing/creating/Editing Course Schedule');

            return back()->with('error', $e->getMessage());
        }
    }

    public function DynamicCoursePagesStore(Request $request)
    {
        try {
            $this->repository->DynamicCoursePagesStore($request);

            return redirect()->route('dynamic-course-pages-list')
                ->with('success', 'Course page saved successfully.');
        } catch (\Exception $e) {
            catch_log($e, 'Creating Course Course');

            return back()->with('error', $e->getMessage());
        }
    }

    public function CourseDynamicPageDestroy($id)
    {
        try {
            $data = $this->repository->CourseDynamicPageDestroy($id);

            if (!$data['status']) {
                if (request()->ajax()) {
                    return response()->json(['status' => false, 'error' => $data['message']]);
                }
                return ['status' => false, 'message' => $data['message']];
            }
            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => $data['message']]);
            }
            return ['status' => true, 'message' => $data['message']];

        } catch (\Exception $e) {
            catch_log($e, "Deleting Course Dynamic Pages");
            if (request()->ajax()) {
                return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
            }
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function CourseYoutubeStore(Request $request)
    {
        try {
            $data = $this->repository->CourseYoutubeStore($request);
            // dd($data);
            if (! $data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }

            return redirect()->route('course-you-tube-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Creating Course Course');

            return back()->with('error', $e->getMessage());
        }
    }

    public function CourseScheduleDestroy($id)
    {
        try {
            $data = $this->repository->CourseDestroy($id);
            if (! $data['status']) {
                return response()->json(['status' => false, 'error' => $data['message']]);
            }
            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => $data['message']]);
            }

            return response()->json(['status' => true, 'message' => $data['message']]);
        } catch (\Exception $e) {
            catch_log($e, 'Deleting Course Course');

            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_filtered_Course(Request $request)
    {
        try {
            $data = $this->repository->get_filtered_Course($request);

            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            catch_log($e, 'Getting filtered Course Course');

            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function coursetrainerPagesList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->CourseTrainersList();
                $query = $this->repository->CourseTrainersListFilters($query, $request)->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->addColumn('Coursedate', function ($row) {
                        return $row?->Coursedate?->course_title;
                    })
                    ->addColumn('CourseCountryName', function ($row) {
                        return $row?->CourseCountryName?->name;
                    })
                    ->addColumn('ISO2', function ($row) {
                        return $row?->CourseCountryName?->iso2;
                    })
                    ->addColumn('trainer_name', function ($row) {
                        return $row?->trainer_name;
                    })
                    ->addColumn('trainer_experience', function ($row) {
                        return $row?->trainer_experience;
                    })

                    ->addColumn('created_by', function ($row) {
                        return $row?->creator->name;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);

                        $edit_url = route('course-trainer-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="'.$view_url.'" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="'.$edit_url.'" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';

                        $dropdown .= '</ul></div></div>';

                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'Coursedate','ISO2', 'CourseCountryName', 'trainer_experience', 'trainer_name',  'created_by', 'created_at'])
                    ->make(true);
            }

            return view('Course::coursetrainer.index');
        } catch (\Exception $e) {
            catch_log($e, 'Getting list of Course Trainer data');
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function coursetrainerCreate(Request $request)
    {
        try {
            $data = $this->repository->coursetrainerCreate($request);

            return $data;
        } catch (\Exception $e) {
            catch_log($e, 'Viewing/creating/Editing Course Schedule');

            return back()->with('error', $e->getMessage());
        }
    }

    public function coursetrainerStore(Request $request)
    {
        try {
            $data = $this->repository->coursetrainerStore($request);
            // dd($data);
            if (! $data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }

            return redirect()->route('course-trainer-list')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Creating Course Course');

            return back()->with('error', $e->getMessage());
        }
    }

    // public function DynamicPagesData(Request $request)
    // {
    //     try {
    //         $fileName = 'dynamic_course_data_' . time() . '.csv';
    //         $folderPath = public_path('uploads/temp_downloads');

    //         // Create folder if it doesn't exist
    //         if (!File::exists($folderPath)) {
    //             File::makeDirectory($folderPath, 0777, true);
    //         }

    //         $filePath = $folderPath . '/' . $fileName;
    //         $handle = fopen($filePath, 'w');

    //         // Add CSV headers
    //         fputcsv($handle, ['ID', 'Country Id', 'state Id', 'Course Name', 'Country', 'State', 'H1 Tag', 'Created At']);

    //         // Chunk data to prevent memory issues
    //         DB::table('tbl_dynamic_course_headline as d')
    //             ->leftJoin('tbl_country as c', 'd.country_id', '=', 'c.id')
    //             ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
    //             ->leftJoin('tbl_courses as h', 'd.course_id', '=', 'h.id')
    //             ->select([
    //                 'd.id',
    //                  'd.country_id',
    //                  'd.state_id',
    //                 'h.course_title as courseName',
    //                 'c.name as country_name',
    //                 's.name as state_name',
    //                 'd.hone_tag',
    //                 'd.created_at'
    //             ])
    //             ->orderBy('d.id', 'DESC')
    //             ->chunk(1000, function ($rows) use ($handle) {
    //                 foreach ($rows as $row) {
    //                     fputcsv($handle, [
    //                         $row->id,
    //                         $row->country_id,
    //                         $row->state_id,
    //                         $row->courseName,
    //                         $row->country_name,
    //                         $row->state_name,
    //                         $row->hone_tag,
    //                         $row->created_at
    //                     ]);
    //                 }
    //             });

    //         fclose($handle);

    //         // Success message and pass the file URL to the next page
    //         $downloadUrl = asset('uploads/temp_downloads/' . $fileName);

    //         // return redirect()->route('dynamic-course-pages-list')
    //         //     ->with('success', 'Data exported successfully. Your download will start shortly.')
    //         //     ->with('download_url', $downloadUrl);
    //         return response()->download($filePath)->deleteFileAfterSend(true);

    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }

    public function DynamicPagesData(Request $request)
    {
        try {

            $fileName = 'dynamic_course_data_' . time() . '.csv';

            return response()->streamDownload(function () {

                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'ID',
                    'Country Id',
                    'State Id',
                    'Course Name',
                    'Country',
                    'State',
                    'H1 Tag',
                    'Created At'
                ]);

                // Chunk to avoid memory issues
                DB::table('tbl_dynamic_course_headline as d')
                    ->leftJoin('tbl_course_country as c', 'd.country_id', '=', 'c.id')
                    ->leftJoin('tbl_state as s', 'd.state_id', '=', 's.id')
                    ->leftJoin('tbl_courses as h', 'd.course_id', '=', 'h.id')
                    ->select([
                        'd.id',
                        'd.country_id',
                        'd.state_id',
                        'h.course_title as courseName',
                        'c.name as country_name',
                        's.name as state_name',
                        'd.hone_tag',
                        'd.created_at'
                    ])
                    ->orderBy('d.id', 'DESC')
                    ->chunk(1000, function ($rows) use ($handle) {

                        foreach ($rows as $row) {

                            fputcsv($handle, [
                                $row->id,
                                $row->country_id,
                                $row->state_id,
                                $row->courseName,
                                $row->country_name,
                                $row->state_name,
                                $row->hone_tag,
                                $row->created_at,
                            ]);
                        }
                    });

                fclose($handle);

            }, $fileName, [
                'Content-Type' => 'text/csv',
            ]);

        } catch (\Throwable $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
