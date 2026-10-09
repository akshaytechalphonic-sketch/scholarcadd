<?php

namespace  Modules\Workflow\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WorkflowAssignmentRepository
{
    public function yajraList(Request $request)
    {
        $companyId = Controller::loginandcompany();
        $step_id = decrypt($request->step_id);
            return $query = Controller::model('WorkflowAssignment','Workflow')::with('step_condition')->
                where('company_id', $companyId[1])
                ->where('workflow_step_id', $step_id)
                ->orderby('id','DESC');
    }
    public function list(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $stp_id = decrypt($request->id);
        $step_data = Controller::model('WorkflowSteps','Workflow')::ActiveOnly($company_id)->find($stp_id);
        if($step_data){
            if($step_data?->condition=='Line Department' || $step_data?->condition=='Signatory'){
                return redirect()->back()->with(['error' =>'Can not assign in case of '.$step_data?->condition.' !']);
            }
            $users = get_employee();
            $departments = masterParentData('Department');
            $step_id = encrypt($stp_id);
            $setup_id = encrypt($step_data?->workflow_setup_id);
            $count = Controller::model('WorkflowAssignment','Workflow')::where('company_id',$company_id)->where('workflow_step_id', $stp_id)->count();
            return view('workflow::workflow_master.workflowassignment.index', compact('count','setup_id','step_id','step_data','users','departments'));
        }
    }

    public function add(Request $data)
    {
        try{
            $step_id = decrypt($data->step_id);
            $setup_id = decrypt($data->setup_id);

            $cmp_and_user = Controller::loginandcompany();
            $mod = Controller::model('WorkflowAssignment','Workflow');
            $new_record = new $mod();
            $new_record->company_id = $cmp_and_user[1];
            $new_record->workflow_setup_id=$setup_id;
            $new_record->workflow_step_id = $step_id;
            if($data->condition_user){
                $new_record->employee_id =$data->condition_user;
            }
            else if($data->condition_dept){
                $new_record->dept_id = $data->condition_dept;
            }
            else if($data->condition_LM){
                $new_record->level = $data->condition_LM;
            }
            $new_record->created_by = $cmp_and_user[0];
            $new_record->updated_by = $cmp_and_user[0];
            $new_record->save();
            return [true,'Record is created successfully'];

        }
        catch (Exception $e) {
            return [false,$e->getMessage()];
        }
    }

    public function show($id)
    {
        $id = decrypt($id);
        $wx_data = Controller::model('WorkflowAssignment','Workflow')::find($id);
        return $wx_data;

    }

    public function edit(Request $request)
    {
        try{
            $id = decrypt($request->asgn_id);
            $wx_data = Controller::model('WorkflowAssignment','Workflow')::find($id);
            if($request->condition_user){

                $wx_data->employee_id = $request->condition_user;
            }
            else if($request->condition_dept){

                $wx_data->dept_id = $request->condition_dept;
            }
            else if($request->condition_LM){

                $wx_data->level = $request->condition_LM;
            }
            $wx_data->save();
           return [true,'Record Updated Successfully'];
        }
        catch (Exception $e) {
            return [false,$e->getMessage()];
        }

    }


}


