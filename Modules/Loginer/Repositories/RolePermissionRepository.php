<?php

namespace  Modules\Loginer\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\TwoFactorAuth;
use Illuminate\Support\Arr;

class RolePermissionRepository
{
    use TwoFactorAuth;

    public static function getAllRoles()
    {
        return Role::where('guard_name','web');
    }


    public function getAllPermissions()
    {
        return Permission::where('guard_name','web');
    }

    public function editRole(Request $request)
    {
        $id = decrypt($request->id);
        return Role::find($id);
    }

    public function storeRole(string $name, array $permissions = []): Role
    {
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }
        $role = Role::create([
            'name' => $name,
            'guard_name' => 'web',
        ]);
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        return $role;
    }

    public function updateRole(Role $role, string $name, array $permissions = []): Role
    {
        $role->update(['name' => $name]);
        $role->syncPermissions($permissions);
        return $role;
    }


    public function deleteRole(Role $role): bool
    {
        return $role->delete();
    }

    public function storePermission(Request $request)
    {
        $name = $request->name;
        $permission = Permission::create(['name' => $name]);
        return  $permission ;

    }

    public function deletePermission(Request $request)
    {
        $name = $request->name;
        $permission = Permission::where('name', $name)->first();
        $users = Controller::model('User')::permission($name)->get();
        foreach ($users as $user) {
            $user->revokePermissionTo($name);
        }
        $roles = Role::permission($name)->get();
        foreach ($roles as $role) {
            $role->revokePermissionTo($name);
        }
        return $permission->delete();
    }




    public function assignRoleToUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $role = $request->role;
        $user->syncRoles([$role]);
    }

    public static function assignRoleToUser_new(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $role = $request->role;
        $user->assignRole([$role]);
        return true;
    }


    public function removeRoleFromUser($user, string $role): void
    {
        $user->removeRole($role);
    }


    public function givePermissionToRole(Role $role, string $permission): void
    {
        $role->givePermissionTo($permission);
    }


    public function revokePermissionFromRole(Role $role, string $permission): void
    {
        $role->revokePermissionTo($permission);
    }


    public function syncPermissionsToRole(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }

    public function userEdit(Request $request)
    {
        $id = decrypt($request->id);
        $user = Controller::model('User')::find($id);
        $company_access = false;
        $emps = [];
        $emp_access = false;
        if($user?->type == "Employee" || $user?->type == "User"){
            $company_access = true;
        }
        if($user?->type == "Employee"){
            $emps = Controller::model('Employee','DynamicMaster')::where('user_id',null)->orWhere('user_id',$id)->get();
            $emp_access = true;
        }
        $company = Controller::Model('Company','DynamicMaster')::ActiveOnly()->get();
        $roles = Role::get();
        $userRole = $user->roles->pluck('name','name')->all();
        $selectedcompany = Controller::helper('CommonHelper')::companies_by_doc_id('user_id',$id);
        $final_data = ['user'=>$user,'company_access'=>$company_access,'emps'=>$emps,'emp_access'=>$emp_access,'company'=>$company,
                        'roles'=>$roles,'userRole'=>$userRole,'selectedcompany'=>$selectedcompany];
        return $final_data;
    }

    public function userList()
    {
        return User::where('status','Active')->orderby('id','desc');
    }

    public function userUpdate(Request $request)
    {
        try {
            Controller::db()::beginTransaction();
            $input = $request->all();
            if(!empty($input['password'])){
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input,array('password'));
            }
            $id = decrypt($request->id);
            $user = Controller::model('User')::find($id);

            if($user?->type == "Employee" && $request->employee_id){
                $emp = Controller::model('Employee','DynamicMaster')::find($request->employee_id);
                if($emp){
                    $emp->user_id = $id;
                    $emp->save();
                    $user->emp_id = $request->employee_id;
                }
                else{
                    Controller::db()::rollback();
                   return ['status'=>false,'message'=>'Employee Not Found'];
                }

            }
            if($user?->type == "Employee" || $user?->type == "User"){
                Controller::model('UserCompany','DynamicMaster')::where('user_id',$id)->delete();
               $i =0;
                foreach($request->company_id as $index =>$cmp_id){
                    $model = Controller::model('UserCompany','DynamicMaster');
                    $user_company = new $model();
                    $user_company->user_id = $id;
                    $user_company->company_id = $cmp_id;
                    $user_company->status = 'Active';
                    $user_company->save();
                    $i =$i++;
                }
                $user->save();
            }
            $user->save();
            if($request->enable_2fa)
            {
                $fa_enable = $this->two_fa_setup('on',$user->id);
                if(!$fa_enable['data']){
                    Controller::db()::rollback();
                    return [ 'status' => false,'message' => $fa_enable['message']];
                }
            }
            else{
                $fa_enable = $this->two_fa_setup('off',$user->id);
            }
            Controller::db()::table('model_has_roles')->where('model_id',$id)->delete();
            $user->syncRoles($request->input('roles'));
            Controller::db()::commit();
            return ['status'=>true,'message'=>'User details Updated Successfully'];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            return ['status'=>false,'message'=>$e->getMessage()];

        }
    }

    public function viewUser(Request $request)
    {
        $id = decrypt($request->userId);
        $user = Controller::model('User')::with('company')->find($id);
        $companies = Controller::model('UserCompany','DynamicMaster')::where('user_id',$id)->get();
        $companiesInfo = [];
        foreach($companies as $company){
            $companyModel = Controller::model('Company','DynamicMaster')::find($company->id);
            if ($companyModel) {
                $companiesInfo[] = ['id' => $companyModel->id,'name' => $companyModel->name];
            }
        }
        $id=encrypt($user->id);
        $final_data=['status'=>true,'id'=>$id,'user'=>$user,'companiesInfo'=>$companiesInfo];
        return $final_data;
    }

    public function addUser()
    {
        $cmp_and_user = Controller::loginandcompany();
        $employee = Controller::model('Employee','DynamicMaster')::where('company_id',$cmp_and_user[1])
        ->where('status','Active')->where('user_id',null)->get();
        $company = Controller::Model('Company','DynamicMaster')::ActiveOnly()->get();
        $roles = Role::get();
        $final_data = ['employee'=>$employee,'company'=>$company,'roles'=>$roles];
        return $final_data;
    }

    public function storeUser(Request $request)
    {
         try {
            Controller::db()::beginTransaction();

            $emp_id = null;
            if($request->emp_id){
                $emp_id = decrypt($request->emp_id);
            }
            $data = Controller::helper('CommonHelper')::create_user($request->name,$request->email,$emp_id,$request->password,'User');
            $data->emp_id = $emp_id;
            $data->save();

            $emps = Controller::model('Employee','DynamicMaster')::find($emp_id);
            if($emps){
                $emps->user_id = $data->id;
                $emps->email = $request->email;
                $emps->save();
            }

            foreach($request->company_id as $cmp_id){
                $model = Controller::model('UserCompany','DynamicMaster');
                $user_company = new $model();
                $user_company->user_id = $data->id;
                $user_company->company_id = $cmp_id;
                $user_company->status = 'Active';
                $user_company->save();
            }

             $data->syncRoles($request->roles);
            Controller::db()::commit();
            return ['status'=>true,'message'=>'User Created Successfully'];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            return ['status'=>false,'message'=>$e->getMessage()];
        }
    }


}
