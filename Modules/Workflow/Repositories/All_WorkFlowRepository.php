<?php

namespace  Modules\Workflow\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class All_WorkFlowRepository
{
    public function setup_list()
    {
        $companyId = Controller::loginandcompany();
        return Controller::model('WorkflowSetup','Workflow')::where('company_id', $companyId[1])->orderby('id','DESC');
    }

    public function show_setup($id)
    {
        $id = decrypt($id);
        $data = Controller::model('WorkflowSetup','Workflow')::with('apply_on')->find($id);
        return $data;
    }

    public function edit_setup(Request $request)
    {
        $id = decrypt($request->wf_id);
        $wx_data = Controller::model('WorkflowSetup','Workflow')::find($id);
        $wx_data->workflow_name = $request->w_name;
        $wx_data->status = $request->w_status;
        $wx_data->save();
        return true;
    }

    public function add_setup(Request $request)
    {
       $cmp_and_user = Controller::loginandcompany();
        $mod = Controller::model('WorkflowSetup','Workflow');
        $new_record = new $mod();
        $new_record->company_id = $cmp_and_user[1];
        $new_record->workflow_code=$request->w_code;
        $new_record->apply_on_id=$request->apply_on_id;
        $new_record->workflow_name = $request->w_name;
        $new_record->status = $request->w_status;
        $new_record->created_by = $cmp_and_user[0];
        $new_record->updated_by = $cmp_and_user[0];
        $new_record->save();
        return $new_record->id;
    }

    //Steps

    public function step_list(Request $request)
    {
        $setup_id = decrypt($request->setup_id);
        $companyId = Controller::loginandcompany();
        return Controller::model('WorkflowSteps','Workflow')::with('workflowCode_Name')->
                where('company_id',  $companyId[1])
                ->where('workflow_setup_id', $setup_id);
    }

    public function show_step($id)
    {
        $id = decrypt($id);
        $wx_data = Controller::model('WorkflowSteps','Workflow')::with('setup','setup.apply_on','conditions')->find($id);
        return $wx_data;
    }

    public function edit_step(Request $request)
    {
        $wfs_id = decrypt($request->step_id);
        $wx_data = Controller::model('WorkflowSteps','Workflow')::find($wfs_id);
        $wx_data->step_name = $request->step_name;
        $wx_data->status= $request->step_status;
        $wx_data->no_of_approver = $request->no_of_approver;

        if($request->system_notification)
            $wx_data->system_notification='Yes';
        else
            $wx_data->system_notification='No';
        if($request->email_notification){
            if($request->email_template_id){
                $wx_data->email_notification='Yes';
                $wx_data->email_template_id=$request->email_template_id;
            }
            else
                $wx_data->email_notification='No';
        }
        else
            $wx_data->email_notification='No';

        $wx_data->save();
        return true;
    }

    public function add_step(Request $request)
    {
        try{
            $wf_id = decrypt($request->wf_id);
            $cmp_and_user = Controller::loginandcompany();
            $mod = Controller::model('WorkflowSteps','Workflow');
            $new_record = new $mod();
            if($request->system_notification)
                $new_record->system_notification='Yes';
            else
                $new_record->system_notification='No';
            if($request->email_notification){
                if($request->email_template_id){
                    $new_record->email_notification='Yes';
                    $new_record->email_template_id=$request->email_template_id;
                }
                else{
                    $new_record->email_notification='No';
                }
            }
            else{
                $new_record->email_notification='No';
            }
            $new_record->company_id = $cmp_and_user[1];
            $new_record->workflow_setup_id=$wf_id;
            $new_record->step_name = $request->w_name;
            $new_record->condition = $request->w_condition;
            $new_record->no_of_approver = $request->no_of_approver;
            $new_record->status = $request->status;
            $new_record->created_by = $cmp_and_user[0];
            $new_record->updated_by = $cmp_and_user[0];
            $new_record->save();

            if($request->w_condition == "Conditional"){
                $mod = Controller::model('WorkflowSteps','Workflow');
                $new_record1 = new $mod();
                $new_record1->system_notification = $new_record->system_notification;
                $new_record1->email_notification=$new_record->email_notification;
                $new_record1->email_template_id=$new_record->email_template_id;
                $new_record1->company_id = $cmp_and_user[1];
                $new_record1->workflow_setup_id=$wf_id;
                $new_record1->step_name = $request->w_name.".1";
                $new_record1->condition = $request->w_condition;
                $new_record1->no_of_approver = $request->no_of_approver;
                $new_record1->status = $request->status;
                $new_record1->substep_type = "True";
                $new_record1->created_by = $cmp_and_user[0];
                $new_record1->updated_by = $cmp_and_user[0];
                $new_record1->save();

                $mod = Controller::model('WorkflowSteps','Workflow');
                $new_record2 = new $mod();
                $new_record2->system_notification = $new_record->system_notification;
                $new_record2->email_notification=$new_record->email_notification;
                $new_record2->email_template_id=$new_record->email_template_id;
                $new_record2->company_id = $cmp_and_user[1];
                $new_record2->workflow_setup_id=$wf_id;
                $new_record2->step_name = $request->w_name.".2";
                $new_record2->condition = $request->w_condition;
                $new_record2->no_of_approver = $request->no_of_approver;
                $new_record2->status = $request->status;
                $new_record2->created_by = $cmp_and_user[0];
                $new_record2->updated_by = $cmp_and_user[0];
                $new_record2->substep_type = "False";
                $new_record2->save();

            }
            return $new_record->id;
        }
        catch (Exception $e) {
            return null;
        }
    }

    public function save_conditional_steps(Request $request)
    {
        try{
            $cmp_and_user = Controller::loginandcompany();
            $step_id = decrypt($request->wf_step_id);            
            $step_data = Controller::model('WorkflowSteps','Workflow')::find($step_id);
            if($step_data && $request->condition_value && count($request->condition_value)>0){
                $i=0;

                $old_line = Controller::model('Workflow_Condition','Workflow')::where('workflow_step_id',$step_id)->get();
                if(count($old_line)>0)
                    Controller::db()::delete('delete from tbl_workflow_condition where workflow_step_id = ? ', [$step_id]);

                foreach($request->condition_value as $cndn){
                    $model = Controller::model('Workflow_Condition','Workflow');
                    $data = new $model();
                    $data->workflow_setup_id = $step_data->workflow_setup_id;
                    $data->workflow_step_id = $step_id;
                    $data->field = $request->field[$i];
                    $data->condition = $request->condition[$i];
                    $data->condition_value = $cndn;
                    $data->company_id = $cmp_and_user[1];
                    $data->created_by = $cmp_and_user[0];
                    $data->save();
                    $i++;
                }
                return ['status'=>true];
            }
            else{
                 return ['status'=>false];
            }
        }
        catch (Exception $e) {            
            return ['status'=>false];
        }
    }

    public function workflow_steps_get_next($id)
    { 
        $cmp_and_user = Controller::loginandcompany();
        $step_id = decrypt($id);
        $step_data = Controller::model('WorkflowSteps','Workflow')::find($step_id);
        $setup_id = $step_data?->workflow_setup_id;
        $option=["<option value=''>--Choose option--</option>"];

        $first_id = Controller::model('WorkflowSteps','Workflow')::where('workflow_setup_id',$setup_id)->pluck('id')->first();

        $all_ids = Controller::model('WorkflowSteps','Workflow')::where('substep_type',null)->where('workflow_setup_id',$setup_id)->where('status','Active')->pluck('id')->toArray();
        $assign_ids = Controller::model('WorkflowSteps','Workflow')::where('workflow_setup_id',$setup_id)->where('status','Active')->pluck('next_step_id')->toArray();

        $next_ids = array_diff($all_ids,$assign_ids);
        $next_ids = array_diff($next_ids,[$step_id,$first_id]);
        if(!$step_data?->substep_type){
            $next_ids[] = intVal($step_data?->next_step_id);
        }
        $steps = Controller::model('WorkflowSteps','Workflow')::whereIn('id',$next_ids)->where('status','Active')->select('id','step_name')->get();
        foreach($steps as $stp){
            if($stp->id == $step_data?->next_step_id){
                $option[] = "<option value=" . $stp->id . " selected>$stp->step_name</option>";
            }
            else{
                $option[] = "<option value=" . $stp->id . ">$stp->step_name</option>";
            }
        }
        return [$option,$step_data?->step_name];
    }

    public function workflow_steps_store_next(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $step_id = decrypt($request->wf_next_step_id);        
        $step_data = Controller::model('WorkflowSteps','Workflow')::where('status','Active')->find($step_id);
        $step_data->next_step_id = $request->next_step_id;
        $step_data->save();        
        return $step_data->workflow_setup_id;
    }

    public function wf_setup_validation(Request $request)
    {
        $id = decrypt($request->w_id);        
        return Controller::helper('WorkflowHelper','Workflow')::wf_setup_validate($id); 

    }



}

