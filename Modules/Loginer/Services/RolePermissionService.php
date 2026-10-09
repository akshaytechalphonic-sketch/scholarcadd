<?php

namespace Modules\Loginer\Services;

use Modules\Loginer\Repositories\RolePermissionRepository;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use DataTables;

class RolePermissionService
{
    protected RolePermissionRepository $repository;

    public function __construct(RolePermissionRepository $reposity)
    {
        $this->repository = $reposity;
    }

    public function getAllRoles(Request $request)
    {
        try {

            if ($request->ajax()) {

            $query = $this->repository->getAllRoles()
            ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='name',function ($query) use ($request){
                $query->where('name', $request->custom_filter[1]);
            });

            return DataTables::of($query)
            ->addColumn('no_of_permission', function ($row) {
                return $row?->permissions->count();
            })
            ->addIndexColumn()
            ->addColumn('created_by', function ($row) {
                return $row?->creator?->name;
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $view_url = route('roles-edit',['id'=>$incr]);
                $delete_url = route('roles-destroy',['id'=>$incr]);
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';

                $dropdown.= '<li> <a href="'.$view_url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-eye-line"></i> Edit</a> </li>';
                $dropdown.= '<li> <a href="'.$delete_url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-eye-line"></i> Delete </a> </li>';

                $dropdown.= '</ul> </div></div>';
                    return $dropdown;
                })
                ->rawColumns(['actions','status'])
            ->make(true);

            }



            return view('loginer::role.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            catch_log($e,"Getting list of Role data");
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

    public function createRole(Request $request)
    {
        $assign_pers = [];
        if($request->id){
            $data = $this->repository->editRole($request);
            if($data->permissions && count($data->permissions)){
                foreach($data->permissions as $per){
                    $assign_pers[] = $per?->name;
                }
            }
        }
        else{
            $data = null;
        }

        $permissions = $this->repository->getAllPermissions()->get();

        return view('loginer::role.add',compact('data','permissions','assign_pers'));
    }


    public function listPermissions(Request $request)
    {
        try {

            if ($request->ajax()) {

            $query = $this->repository->getAllPermissions()
            ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='name',function ($query) use ($request){
                $query->where('name', $request->custom_filter[1]);
            });

            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('created_by', function ($row) {
                return $row?->creator?->name;
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $delete_url = route('permissions-destroy',['name'=>$row?->name]);
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';

                $dropdown.= '<li> <a href="'.$delete_url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-eye-line"></i> Delete </a> </li>';

                $dropdown.= '</ul> </div></div>';
                    return $dropdown;
                })
                ->rawColumns(['actions','status'])
            ->make(true);

            }
            return view('loginer::permission.index');
        } catch (\Throwable $e) {
            catch_log($e,"List Permissions Error");
            return error('Failed to fetch permissions.', false);
        }
    }

    // public function editRole(Request $request)
    // {
    //     try {
    //         $permissions = $this->repository->editRole();
    //         return success('Permissions fetched successfully.', null, $permissions);
    //     } catch (\Throwable $e) {
    //         catch_log($e,"Edit Role Error");
    //         return error('Failed to fetch permissions.', false);
    //     }
    // }

    public function storeRole(array $data)
    {
        try {
            $role = $this->repository->storeRole($data['name'], $data['permissions'] ?? []);
             return redirect()->route('roles-list')->with('success','Role created successfully');
        } catch (\Throwable $e) {
            catch_log($e,"Store roles");
            return back()->with('error',$e->getMessage());
        }
    }

    public function updateRole(Role $role, array $data)
    {
        try {
            $updatedRole = $this->repository->updateRole($role, $data['name'], $data['permissions'] ?? []);
            return redirect()->route('roles-list')->with('success','Role Updated successfully');
        } catch (\Throwable $e) {
            catch_log($e,"Update Role Error");
            return back()->with('error',$e->getMessage());
        }
    }

    public function deleteRole(Role $role)
    {
        try {
            $this->repository->deleteRole($role);
             return redirect()->route('roles-list')->with('success','Role Deleted successfully');
        } catch (\Throwable $e) {
            catch_log($e,"Delete Role Error");
            return back()->with('error',$e->getMessage());
        }
    }

    public function storePermission(Request $request)
    {
        try {
            $role = $this->repository->storePermission($request);
             return response()->json([
                "status"=>true,
                "success"=>'Permission created successfully'
             ]);
        } catch (\Throwable $e) {
            catch_log($e,"Store Permission");
             return response()->json([
                "status"=>false,
                "error"=>$e->getMessage()
             ]);
        }
    }


    public function deletePermission(Request $request)
    {
        try {
            $this->repository->deletePermission($request);
             return redirect()->route('permissions-list')->with('success','Permission Deleted successfully');
        } catch (\Throwable $e) {
            catch_log($e,"Delete Permission Error");
            return back()->with('error',$e->getMessage());
        }
    }

    public function assignRoleToUser(Request $request)
    {
        try {
            $result = $this->repository->assignRoleToUser($request);
            return back()->with('success','Role assigned to user successfully.');
        } catch (\Throwable $e) {
            catch_log($e,"Assign Role Error");
            return error('Failed to assign role to user.', false);
        }
    }

    public function userEdit(Request $request)
    {
        try {
            $data = $this->repository->userEdit($request);
            return view('loginer::user.edit',compact('data'));
        } catch (\Throwable $e) {
            catch_log($e,"User Edit Error");
            return back()->with('error',$e->getMessage());
        }
    }

    public function userUpdate(Request $request)
    {
        try {
            $data = $this->repository->userUpdate($request);
           if (!$data['status']) {
                catch_log(new \Exception($data['message']), 'Update user');
                return back()->with('error', $data['message'])->withInput();
            }
            return back()->with('success', $data['message']);
        } catch (\Throwable $e) {

            catch_log($e,"User Edit Error");
            return back()->with('error',$e->getMessage());
        }
    }

     public function userList(Request $request)
    {
        try {
            if ($request->ajax()) {
            $users = $this->repository->userList()
                ->when($request->custom_filter && is_array(request('custom_filter')),function ($query) use ($request){
                if ($request->custom_filter[0] === 'employee_email') {
                    $query->whereHas('employee', function ($q) use ($request) {
                        $q->where('email', $request->custom_filter[1]);
                    });
                }
                    else if($request->custom_filter[0]==='roles'){
                        $query->whereHas('roles', function ($q) use ($request) {
                            $q->where('name', $request->custom_filter[1]);
                        });
                    }
                    else {
                        $query->where($request->custom_filter[0], $request->custom_filter[1]);
                    }
                });
             return datatables()->of($users)
                ->addIndexColumn()
                ->addColumn('employee_email', function ($row) {
                    return $row?->Employee?->email;
                })
                ->addColumn('roles', function ($row) {
                    $roles = $row?->roles;
                    $allRoles = '';
                    foreach ($roles as $role) {
                        $allRoles .= '<span class="bg-gray-400 py-1 px-3 rounded-md " style="color:white; margin:2px;">' . $role->name . '</span>';
                    }
                    return $allRoles;
                })
                ->addColumn('action', function ($row) {
                    $id = encrypt($row?->id);
                    $userId ="'$id'";
                    $model_name = "'view-sidebar'";
                    $editURL = route('users-edit', ['id' => $id]);

                    $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i></button>
                                <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                    $dropdown.= '<li><button onclick="view_model('.$model_name.',' . $userId . ')" class="block w-full  py-2 px-6 hover:bg-gray-100"> View <i class="ri-eye-line text-primary"></i></a></button> </li>';
                    $dropdown.= '<li><a href="'.$editURL.'"><button class="block w-full  py-2 px-6 hover:bg-gray-100"> Edit <i class="ri-pencil-line text-primary"></i></a></button> </li>';
                    $dropdown.= '</ul> </div> </div>';

                    return $dropdown;
                })
                ->rawColumns(['roles','employee_email','action'])
            ->make(true);

            }
            return view('loginer::user.index');
        } catch (\Throwable $e) {
            catch_log($e,"List Permissions Error");
            return error('Failed to fetch permissions.', false);
        }
    }

    public function viewUser(Request $request)
    {
        try {
            $data = $this->repository->viewUser($request);
            if($data['status'])
            {
                return response()->json(['status'=>true,'id'=>$data['id'],'user'=>$data['user'],'companiesInfo'=>$data['companiesInfo']]);
            }
            return response()->json(['status'=>false,'error'=>'Something went wrong']);
        } catch (\Throwable $e) {
            catch_log($e,"User View Error");
            return response()->json(['status'=>false,'error'=>$e->getMessage()]);
        }
    }

    public function addUser()
    {
        try {
            $data = $this->repository->addUser();
            $employee = $data['employee'];
            $company = $data['company'];
            $roles = $data['roles'];
            return view('loginer::user.add', compact('employee', 'company', 'roles'));
        } catch (\Throwable $e) {
            catch_log($e,"User add Error");
            return back()->with('error',$e->getMessage());
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $data = $this->repository->storeUser($request);
            if($data['status'])
            {
                return redirect()->back()->with('success',$data['message']);
            }
             return redirect()->back()->with('error',$data['message']);

        } catch (\Throwable $e) {

            catch_log($e,"User add Error");
            return back()->with('error',$e->getMessage());
        }
    }
}
