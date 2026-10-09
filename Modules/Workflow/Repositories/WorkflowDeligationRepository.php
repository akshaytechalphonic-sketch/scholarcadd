<?php

namespace  Modules\Workflow\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WorkflowDeligationRepository
{
    public function yajraList(Request $request)
    {
        $companyId = Controller::loginandcompany();
            return  Controller::model('WorkflowDeligation','Workflow')::ActiveOnly($companyId[1])->with('user_name')
                ->orderby('id','DESC');
    }
    public function list(Request $request)
    {
        $users = Controller::model('Employee','DynamicMaster')::all();
        $scopes = Controller::model('WorkflowMap','Workflow')::all();
        return view('workflow::workflow_master.workflowdeligation.index', compact('users','scopes'));

    }

    public function add(Request $data)
    {
        try{
            $mod = Controller::model('WorkflowDeligation','Workflow');
            $cmp_and_user = Controller::loginandcompany();
            $new_record = new $mod();
            $new_record->company_id = $cmp_and_user[1];
            if ($data->scope == "0") {
                $new_record->workflow_id = null;
            } else {
                $new_record->workflow_id = $data->scope;
            }
            $new_record->emp_from = $data->employee_name;
            $new_record->emp_to = $data->delegate_to;
            $new_record->start_date = $data->start_date;
            $new_record->end_date = $data->end_date;
            $new_record->status = $data->status;
            $new_record->created_by = $cmp_and_user[0];
            $new_record->updated_by = $cmp_and_user[0];
            $new_record->save();
            return [true,'Record is created successfully'];

        }
        catch (Exception $e) {
            return [false,$e->getMessage()];
        }
    }

    public function markInactive($id)
    {
        $id = decrypt($id);
        $wx_data = Controller::model('WorkflowDeligation','Workflow')::find($id);
        $wx_data->status = 'Inactive';
        $wx_data->save();
        return $wx_data;

    }

}

