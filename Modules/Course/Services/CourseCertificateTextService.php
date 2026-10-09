<?php

namespace Modules\Course\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Modules\Course\Repositories\CourseCertificateTextRepository;

class CourseCertificateTextService extends Controller
{
    protected CourseCertificateTextRepository $repository;

    public function __construct(CourseCertificateTextRepository $repository)
    {
        $this->repository = $repository;
    }

    public function CourseCertificateTextList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->CourseCertificateList();
                $query = $this->repository->CourseCertificateFilters($query, $request)->orderBy('id', 'desc');
                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('courseName', function ($row) {
                        return  $row?->Coursedate?->course_title;
                    })
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description ?? 'N/A', 60);
                    })

                    ->addColumn('countryName', function ($row) {
                        return $row?->country?->name;
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
                        $edit_url = route('course-certificate-text-edit', ['id' => $incr]);

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
                    ->rawColumns(['actions', 'Coursedate', 'description', 'countryName' , 'stateName' , 'created_by', 'created_at'])
                    ->make(true);
            }
            return view('Course::CertificateText.index');
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

    public function CourseCertificateTextCreate($id, $view = null)
    {
        try {
            $data = $this->repository->CourseCertificateTextCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }

    public function CourseCertificateTextStore(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0];
        $data['company_id'] = $cmp_and_user[1];


        try {

            if (isset($data['id'])) {
                $id = decrypt($data['id']);
            $result = $this->repository->CourseCertificateStore($id, $data);
            } else {

                $result = $this->repository->CourseCertificateStore(null , $data);
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCountries()
    {
        try {
            $userModel = get_class(get_model_instance('CourseCountry', 'Course'));
            return $userModel::all();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getStates()
    {
        try {
            $userModel = get_class(get_model_instance('State', 'Course'));
            return $userModel::all();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getStatesByCountry($countryId)
    {
        try {
            $userModel = get_class(get_model_instance('State', 'Course'));
            return $userModel::where('country_id', $countryId)->get();
        } catch (\Exception $e) {
            return [];
        }
    }
    public function getCourses()
    {
        try {
            $userModel = get_class(get_model_instance('Course', 'Course'));
            return $userModel::all();
        } catch (\Exception $e) {
            return [];
        }
    }


}
