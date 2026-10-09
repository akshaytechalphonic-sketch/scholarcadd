<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\EmployeeRepository;
use Modules\Loginer\Repositories\RolePermissionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class EmployeeService extends Controller
{
    protected EmployeeRepository $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {

                $query = $this->repository->yajraList()
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='created_by' && $request->custom_filter[0]!='department',function ($query) use ($request){
                    $query->where($request->custom_filter[0], $request->custom_filter[1]);
                })

                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='created_by',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('creator', function ($subQuery) use ($item) {
                        $subQuery->where('name', $item);
                    });
                })
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='department',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    // $query->whereHas('department',function ($subQuery) use ($item) {
                    //     $subQuery->where('name', $item);
                    // });
                })
                ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',function ($query) use ($request) {
                        $start_date = $request->custom_filter[1];
                        $end_date = $request->custom_filter[2];
                        $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                    }
                )->orderby('id', 'desc');

            $query->when($request->filter, function ($query) use ($request) {
                $query->where('status', $request->filter);
            });


            return DataTables::of($query)
            ->addColumn('status', function ($row) {
                return status_check($row?->status);
            })
            ->addColumn('created_at', function ($row) {
                return date_time_formate($row?->created_at);
            })

            ->addColumn('department', function ($row) {
                return $row?->department?->name;
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $encr_id ="'$incr'";
                $encr_name ="'$row?->name'";
                $encr_code ="'$row?->empCode'";

                $view_url = route('employee-edit',['id'=>$incr]);
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                $dropdown.= '<li> <a href="'.$view_url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class=" ri-eye-line"></i> View</a> </li>';
                if($row?->status == "Active" && !$row?->user_id){
                    $dropdown.= '<li> <a href="#"  onclick="create_user('.$encr_id.','.$encr_name.','.$encr_code.')"   class="open-drawer-btn block px-4 py-2 hover:bg-gray-100"> <i class="ri-user-add-line"></i> Create User</a> </li>';
                }

                $dropdown.= '</ul> </div></div>';
                    return $dropdown;
                })
                ->rawColumns(['actions','status'])
            ->make(true);
            }
            $role = RolePermissionRepository::getAllRoles()->get();

            return view('dynamicmaster::employee.index',compact('role'));

        } catch (\Exception $e) {
            catch_log($e,"Getting list of Employee data");
            if ($request->ajax()) {
                   return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
         try {
            $editData = null;
            $createData = $this->repository->create();
            $data = array_merge(['data' => $editData], $createData);
            return view('dynamicmaster::employee.add',compact('data'));
         }
         catch (\Exception $e) {
            catch_log($e,'Employee Create');
            return back()->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->repository->store($request);

            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Company Store Error');
                return back()->with('error', $response['message'])->withInput();
            }
            return redirect()->route('employee-list')->with('success',$response['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Employee Store Exception');
            return back()->with('error', __('Something went wrong. Please try again.'));
        }
    }

    public function edit(Request $request)
    {
         try {
            $editData = $this->repository->edit($request);
            $createData = $this->repository->create();
            $data = array_merge(['data' => $editData], $createData);
            return view('dynamicmaster::employee.add',compact('data'));
         }
         catch (\Exception $e) {
            catch_log($e);
            return back()->with('error',$e->getMessage());
        }
    }

    public function assign_employee(Request $request)
    {
         try {
            $all_data = $this->repository->assign_employee($request);
            return response()->json([
                'status'=>true,
                'success'=>'Employee Assigned Successfully'
            ]);
         }
         catch (\Exception $e) {
            catch_log($e,'Assign Employee');
            return response()->json([
                'status'=>false,
                'error',$e->getMessage()
            ]);
        }
    }

    public function create_user(Request $request)
    {
         try {
            $response = $this->repository->create_user($request);
             if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Create User from Employee');
                return back()->with('error', $response['message'])->withInput();
            }
            return back()->with('success', $response['message']);
         }
         catch (\Exception $e) {
            catch_log($e , 'Create User from Employee');
            return back()->with('error',$e->getMessage());
        }
    }






}
