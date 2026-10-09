<?php

namespace Modules\Website\Services;

use Modules\Website\Repositories\HiringRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class HiringService extends Controller
{
    protected HiringRepository $repository;

    public function __construct(HiringRepository $repository)
    {
        $this->repository = $repository;
    }

    public function hiringList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->hiringList();
                $query = $this->repository->hiringFilters($query, $request)->orderBy('id', 'desc');
                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('company_name', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('courseName', function ($row) {
                        return  $row?->Coursedate?->course_title;
                    })
                    ->addColumn('companyName', function ($row) {
                        return Str::limit($row->company_name ?? 'N/A', 60);
                    })

                    ->addColumn('JobRole', function ($row) {
                        return $row->job_role;
                    })

                    ->addColumn('jobLink', function ($row) {
                        return $row->job_link;
                    })


                    ->addColumn('jobDescription', function ($row) {
                        return Str::limit($row->job_description ?? 'N/A', 60);
                    })

                    ->addColumn('countryName', function ($row) {
                        return $row?->CourseCountryName?->name;
                    })

                    ->addColumn('stateName', function ($row) {
                        return $row?->state_date?->name;
                    })


                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })

                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('course-you-tube-view', ['id' => $incr]);
                        $edit_url = route('hiring-companies-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        // $dropdown .= '<li><button onclick="confirmDeleteHeader(\'' . $incr . '\')" class="block w-full text-left px-6 py-2 hover:bg-gray-100 text-red-600">
                                    // <i class="ri-delete-bin-line mr-2"></i>Delete</button></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'Coursedate', 'companyName', 'countryName' , 'stateName' , 'created_by', 'JobRole', 'jobLink', 'jobDescription', 'created_at'])
                    ->make(true);
            }
            return view('website::hiringCompanies.index');
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

    public function hiringCreate($id, $view = null)
    {
        try {
            $data = $this->repository->hiringCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }

    public function hiringStore(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0];
        $data['company_id'] = $cmp_and_user[1];


        try {

            if (isset($data['id'])) {
                $id = decrypt($data['id']);
            $result = $this->repository->hiringStore($id, $data);
            } else {

                $result = $this->repository->hiringStore(null , $data);
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }


}
