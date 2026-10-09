<?php

namespace Modules\Enrolled\Services;

use Modules\Enrolled\Repositories\EnrolledCourseCorporateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class EnrolledCourseService extends Controller
{
    protected EnrolledCourseCorporateRepository $repository;

    public function __construct(EnrolledCourseCorporateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function CourseCorporateindex(Request $request)
    {
        // Add business logic here
        try {
            if ($request->ajax()) {
                $query = $this->repository->enrolledIndividual_CorporateList();
                $query = $this->repository->enrolledIndividual_CorporateFilters($query, $request)->orderBy('id', 'desc');
                return DataTables::of($query)

                   ->addColumn('name', function ($row) {
                        return $row->full_name ?? 'N/A';
                    })
                     ->addColumn('email', function ($row) {
                        return $row->email ?? 'N/A';
                    })
                    ->addColumn('course', function ($row) {
                        return $row->Coursedate->course_title ?? 'N/A';
                    })
                    ->addColumn('country', function ($row) {
                        return $row->CourseCountryName->name ?? 'N/A';
                    })
                    ->addColumn('contact_number', function ($row) {
                        return $row->contact_number ?? 'N/A';
                    })
                   ->addColumn('company_name', function ($row) {
                        return $row->company_name ?? 'N/A';
                    })
                    ->addColumn('job_title', function ($row) {
                        return $row->job_title ?? 'N/A';
                    })
                    ->addColumn('group_size', function ($row) {
                        return $row->group_size ?? 'N/A';
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })

                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('enroll-course-corporate-view', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'name','company_name', 'job_title','group_size','course','country','email','contact_number','created_by', 'created_at'])
                    ->make(true);

            }
            return view('enrolled::enrolled-course-corporate-request.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Course data");
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
    public function enrollcourseCorporateview(Request $request)
    {
        try {
        $id = decrypt($request->id);
        $data = Controller::model('LearningCalender', 'DynamicMaster')::with('Coursedate','CourseCountryName')->find($id);
        return view('enrolled::enrolled-course-corporate-request.view', compact('data'));
         } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Course data");
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

     public function CourseIndividualindex(Request $request)
    {
        // Add Individual logic here

        try {
            if ($request->ajax()) {
                $query = $this->repository->enrolledCourse_CorporateList();
                $query = $this->repository->enrolledCourse_CorporateFilters($query, $request)->orderBy('id', 'desc');
                return DataTables::of($query)

                   ->addColumn('name', function ($row) {
                        return $row->full_name ?? 'N/A';
                    })
                     ->addColumn('email', function ($row) {
                        return $row->email ?? 'N/A';
                    })
                    ->addColumn('course', function ($row) {
                        return $row->Coursedate->course_title ?? 'N/A';
                    })
                    ->addColumn('country', function ($row) {
                        return $row->CourseCountryName->name ?? 'N/A';
                    })
                    ->addColumn('contact_number', function ($row) {
                        return $row->contact_number ?? 'N/A';
                    })

                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })

                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('enroll-course-corporate-view', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'name','course','country','email','contact_number','created_by', 'created_at'])
                    ->make(true);

            }
            return view('enrolled::enrolled-course-individual-request.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Course data");
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

    

}
