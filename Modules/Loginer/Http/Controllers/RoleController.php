<?php

namespace Modules\Loginer\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Modules\Loginer\Services\RolePermissionService;
use App\Http\Controllers\Controller;
use Modules\Loginer\Http\Requests\UserAddRequest;

class RoleController extends Controller
{

    protected RolePermissionService $roleService;

    public function __construct(RolePermissionService $role)
    {
        $this->roleService = $role;
    }


    public function getAllRoles(Request $request)
    {
        return $this->roleService->getAllRoles($request);
    }

    public function createRole(Request $request)
    {
        return $this->roleService->createRole($request);
    }

    public function getAllPermissions(Request $request)
    {
        return $this->roleService->listPermissions($request);
    }

    public function storeRole(Request $request)
    {
        $data = $request->only(['name','permissions']);
        return $this->roleService->storeRole($data);
    }

    public function updateExistingRole(Request $request)
    {
        $id = decrypt($request->id);
        $role = Role::findOrFail($id);
        $data = $request->only(['name', 'permissions']);
        return $this->roleService->updateRole($role, $data);
    }

    public function deleteExistingRole($id)
    {
        $id= decrypt($id);
        $role = Role::findOrFail($id);
        return $this->roleService->deleteRole($role);
    }

     public function storePermission(Request $request)
    {

        return $this->roleService->storePermission($request);
    }


    public function deleteExistingPermission(Request $request)
    {

        return $this->roleService->deletePermission($request);
    }

    protected function userList(Request $request)
    {
        return $this->roleService->userList($request);
    }

    protected function userEdit(Request $request)
    {
        return $this->roleService->userEdit($request);
    }

    protected function userUpdate(Request $request)
    {
        return $this->roleService->userUpdate($request);
    }

    protected function assignRoleToUser(Request $request)
    {
        return $this->roleService->assignRoleToUser($request);
    }

    protected function viewUser(Request $request)
    {
        return $this->roleService->viewUser($request);
    }

    protected function addUser(Request $request)
    {
        return $this->roleService->addUser();
    }

    protected function storeUser(UserAddRequest $request)
    {
        return $this->roleService->storeUser($request);
    }

    protected function get_employee_by_id(Request $request)
    {
        $id = decrypt($request->id);
        $employee = get_employee_by_id($id);
        return response()->json($employee);
    }





}
