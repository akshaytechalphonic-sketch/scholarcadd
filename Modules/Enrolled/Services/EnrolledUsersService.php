<?php

namespace Modules\Enrolled\Services;

use Modules\Enrolled\Repositories\EnrolledUsersRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class EnrolledUsersService extends Controller
{
    protected EnrolledUsersRepository $repository;

    public function __construct(EnrolledUsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {

        // Add business logic here

        try {
            if ($request->ajax()) {
                $query = $this->repository->enrolledList();
                $query = $this->repository->enrolledFilters($query, $request)->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->addColumn('request_type', function ($row) {
                        $view_url = route('enrolled-request-individual.views', ['id' => encrypt($row->id)]);
                        return '<a class="text-blue-500 " href="' . $view_url . '">' . "Individual" . '</a>';
                    })
                    ->addColumn('name', function ($row) {
                        return $row->firstname . $row->lastname ?? 'N/A';
                    })
                    ->addColumn('email', function ($row) {
                        return $row->phonecode.$row->email_id ?? 'N/A';
                    })
                    ->addColumn('phone', function ($row) {
                        return $row->phonecode.$row->phone_no ?? 'N/A';
                    })
                    ->addColumn('courseName', function ($row) {
                        return $row->courseName->course_title ?? 'N/A';
                    })
                    ->addColumn('countryName', function ($row) {
                        return $row->countryName->name ?? 'N/A';
                    })
                    ->addColumn('trainingMode', function ($row) {
                        return $row->training_mode ?? 'N/A';
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_time_formate($row?->created_at);
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('enrolled-request-individual.views', ['id' => $incr]);
                        $edit_url = route('enrolled-request-individual.edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        $dropdown .= '<li><button onclick="confirmDeleteHeader(\'' . $incr . '\')" class="block w-full text-left px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i>Delete</button></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'request_type', 'name', 'email', 'phone', 'courseName','trainingMode','countryName'])
                    ->make(true);
            }
            return view('enrolled::enrolled-individual-request.index');
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
    public function enrolledViews($id, $view = null)
    {
        try {
            $data = $this->repository->EnrolledRepository($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }
    public function enrolledStore(Request $request)
    {
        try {
            $data = $this->repository->EnrolledRepository($request);
            // dd($data);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('enrolled-request-individual.views', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Course Course");
            return back()->with('error', $e->getMessage());
        }
    }
}
