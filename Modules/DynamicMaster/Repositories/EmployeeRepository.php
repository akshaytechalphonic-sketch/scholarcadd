<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Traits\TwoFactorAuth;


class EmployeeRepository
{
    use TwoFactorAuth;
    public function create()
    { 
        $cmp_and_user = Controller::loginandcompany();
        $country_data = Controller::model('Country','DynamicMaster')::ActiveOnly()->get();
        $department = masterParentData('Department');
        $position = masterParentData('Department',$cmp_and_user[1]);
        $employee = get_employee($cmp_and_user[1]);
        $cost_center = masterParentData('CostCenter',$cmp_and_user[1]);
        $business_unit = masterParentData('BusinessUnit',$cmp_and_user[1]);


        $final_data = ['country'=>$country_data,'department'=>$department,'position'=>$position,'cost_center'=>$cost_center,
        'business_unit' => $business_unit ,'employee'=>$employee];

        return $final_data;
    }

    public function store($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $user_id = $cmp_and_user[0];
            $model = Controller::model('Employee','DynamicMaster');

            if ($request->cmp_emp_id) {
                $id = decrypt($request->cmp_emp_id);
                $employee =  $model::find($id);
                if (!$employee) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => 'Company not found with the given ID.',
                    ];
                }
                $employee->updated_by = $user_id;
            } else {

                $max_id="emp-0000";
                $employee = new $model();
                $employee->code = $max_id;
                $found_data = Controller::unique_two('Employee','empCode',$request->empCode,'email',$request->email,'DynamicMaster');
                if($found_data){
                   Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => 'Record Already Exist.',
                    ];
                }
                $employee->empCode = $request->empCode;
                $employee->created_by = $cmp_and_user[0];
                $employee->company_id = $cmp_and_user[1];
                $employee->name = $request->name;
                $employee->email = $request->email;
            }


            $employee->city = $request->city;
            $employee->state = $request->state;
            $employee->zip_code = $request->zip_code;
            $employee->country_id = $request->country_id;
            $employee->position_id = $request->position_id;
            $employee->reportto_id = $request->reportto_id;
            $employee->department_id = $request->department_id;
            $employee->contact_no = $request->contact_no;
            $employee->permanent_address = $request->permanent_address;
            $employee->current_address = $request->current_address;
            $employee->status  = $request->status;
            $employee->updated_by = $cmp_and_user[0];
            $employee->cost_center  = $request->cost_center;
            $employee->business_unit = $request->business_unit;
            $employee->save();

            if ($request->hasFile('profile_pic')) {
                $image = Controller::helper('CommonHelper')::folder_name($employee->id,'Employee','Employee Pic',$request->file('profile_pic'),5,'images','DynamicMaster');
                if(empty($image))
                {
                   Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }
                $employee->profile_picture = $image[1];
                $employee->save();

            }
            Controller::db()::commit();

            return [
                'status' => true,
                'message' => 'Employee saved successfully.',
                'data' => $employee,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function yajraList()
    {
         $data = Controller::model('Employee','DynamicMaster')::with('creator')
        ->orderby('id','desc');
        return $data;
    }

    public function edit($request)
    {
        $id = decrypt($request->id);
        $data = Controller::model('Employee','DynamicMaster')::where('id',$id)->first();
        return $data;
    }

    public function assign_employee($request)
    {
        $id = decrypt($request->master_parent_id);
        $data = Controller::model('MasterParent','DynamicMaster')::where('id',$id)->update([
            'employee_id'=> $request->employee_id
        ]);
        return true;
    }

    public function create_user($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $emp_id = decrypt($request->employee_id);
            $cmp_emp = Controller::model('Employee','DynamicMaster')::where('id',$emp_id)->first();

            $role_name=$request->role_ids;

            $found_data = Controller::unique_one('User','email',$cmp_emp->email);
            if($found_data){
                Controller::db()::rollback();
                return [
                    'status' => false,
                    'message' => 'Record Already Exist.',
                ];
            }

            $random = Controller::helper('CommonHelper')::generate_password();
            $data = Controller::helper('CommonHelper')::create_user($cmp_emp->name,$cmp_emp?->email,$cmp_emp?->id,$random,'Employee',$cmp_emp?->profile_picture);
            $data->company_id = $cmp_and_user[1];
            $data->emp_id = $emp_id;
            $data->profile_img = $cmp_emp->profile_picture;
            $data->save();
            $cmp_emp->user_id = $data->id;
            $cmp_emp->save();
            $mfa = Controller::model('SystemControl')::first();
            if($mfa?->two_fa_enable_employee == 'Yes' && (!empty(array_intersect(['Admin', 'Employee'], $role_name))))
            {
                $fa_enable = $this->two_fa_setup('on',$data->id);
                if(!$fa_enable['data']){
                    Controller::db()::rollback();
                    return [ 'status' => false,'message' => $fa_enable['message']];
                }
            } 
            
            $email_teplate = masterParentSingleData('EmailMaster','name','OTP For Creating User');
            $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id,['Employee',$cmp_emp?->email,$random,$cmp_emp->name]);        
            if($mail[0]){
                Controller::helper('EmailHelper')::mail_send($cmp_emp?->email,$mail[0],$mail[1]);
            }
            else{
                Controller::db()::rollback();
                return [ 'status' => false, 'message' =>$mail[1]];
            }
 
            Controller::db()::table('model_has_roles')->where('model_id',$data->id)->delete();
            foreach($request->role_ids as $role_id){
                $data->assignRole($role_id);
            }
            $data->save();
            $cmp_emp->user_id = $data->id;
            $cmp_emp->save();

            Controller::db()::commit();

            return [
                'status' => true,
                'message' => 'User Created successfully.',
                'data' => $data,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }







}

