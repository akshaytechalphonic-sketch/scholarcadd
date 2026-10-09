<?php

namespace Modules\DynamicMaster\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Modules\DynamicMaster\Repositories\TeamApplicationRepository;



class TeamApplicationService extends Controller
{
    protected TeamApplicationRepository $repository;

    public function __construct(TeamApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function ApplicationList(Request $request)
    {
        try {

            if ($request->ajax()) {
                $query = $this->repository->ApplicationList();
                $query = $this->repository->ApplicationFilters($query, $request)->orderBy('id', 'desc');
                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('fullName', function ($row) {
                        return  $row?->full_name;
                    })
                    ->addColumn('email', function ($row) {
                        return $row->email;
                    })
                    ->addColumn('phone', function ($row) {
                        return $row->phone;
                    })
                    ->addColumn('JobTitle', function ($row) {
                        return $row?->job_title;
                    })
                    ->addColumn('groupSize', function ($row) {
                        return $row?->group_size;
                    })
                    ->addColumn('countryName', function ($row) {
                        return $row?->CourseCountryName?->name;
                    })
                   ->addColumn('CourseName1', function ($row) {
                        return $row?->Coursedate?->course_title;
                    })
                    ->addColumn('Created', function ($row) {
                        return $row?->created_at;
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('application-view', ['id' => $incr]);

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

                    ->rawColumns([ 'fullName', 'Created' , 'actions' , 'countryName', 'CourseName1' , 'email', 'phone' , 'JobTitle' ,'groupSize'])
                    ->make(true);
            }
            return view('dynamicmaster::TeamApplication.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Schude data");
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

    public function Applicationview(Request $request)
    {
        try {
        $id = decrypt($request->id);
        $data = Controller::model('TeamApplication', 'DynamicMaster')::with('Coursedate','CourseCountryName')->find($id);
        return view('dynamicmaster::TeamApplication.view', compact('data'));
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
