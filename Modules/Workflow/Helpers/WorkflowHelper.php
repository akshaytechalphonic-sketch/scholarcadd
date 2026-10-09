<?php
namespace Modules\Workflow\Helpers;
use App\Http\Controllers\Controller;

class WorkflowHelper
{

    public static function getSetup_steps($id)
    {
        $companyId = Controller::loginandcompany();
        $setup = Controller::model('WorkflowSetup','Workflow')::ActiveOnly($companyId[1])->find($id);
        $step = Controller::model('WorkflowSteps','Workflow')::ActiveOnly($companyId[1])->where('workflow_setup_id', $id)->get();
        $data = [$setup, $step];
        return ($data);
    }

    public static function getsteps_assignment($step_id)
    {
        $companyId = Controller::loginandcompany();
        $data = [];
        $data = Controller::model('WorkflowAssignment','Workflow')::ActiveOnly($companyId[1])->with('setup')->where('workflow_step_id', $step_id)->get();
        return ($data);

    }


    public static function getsetup_step_assignment($setupid)
    {
        $data = Controller::model('WorkflowSteps','Workflow')::with('wf_assignment.assign_dept', 'wf_assignment.assign_dept.Company_employee', 'wf_assignment.assign_user')->where('workflow_setup_id', $setupid)->get();
        return ($data);
    }

    public static function doument_requester_byrequester_id($emp_id)
    {
        $doc = Controller::model('User')::find($emp_id);
        return $doc?->name;
    }


    public static function requester_for($employee_id)
    {
        $emp_data = Controller::model('Employee','DynamicMaster')::find($employee_id);
        if ($emp_data)
            return "$emp_data?->emp_code" . " - " . "$emp_data?->emp_fname $emp_data?->emp_mname $emp_data?->emp_lname";
        return '';
    }

    public static function updateStatus_line($action_id, $comment, $status)
    {
        $user_id = Controller::user_id();
        $emp_id = Controller::helper('ApplyWorkflowHelper','Workflow')::usertoemp($user_id);
        $data = Controller::model('Workflow_TransitionLine','Workflow')::find($action_id);
        $data->status = $status;
        $data->approval_id = $emp_id;
        $data->reason = $comment;
        $data->date = date('Y-m-d');
        $data->save();
        if ($data->id) {
            return $data->id;
        } else {
            return null;
        }
    }

    public static function transition_current_status($w_id)
    {
        $id = convert_uudecode(base64_decode($w_id));
        $line = Controller::model('Workflow_TransitionLine','Workflow')::find($id);
        if($line){
            $header = Controller::model('Workflow_TransitionHeader','Workflow')::find($line?->workflow_transition_header);
            if($header?->status == 'Active' && $line?->status == 'Pending'){
                return true;
            }
            return false;
        }
        return false;
    }

    public static function check_for_workflow_approval_user($type,$id)
    {
        $emp = Controller::helper('CommonHelper')::user_name_emp_id();
        if($emp?->id){
            $id = intVal($id);
            $map_id = Controller::model('WorkflowMap','Workflow')::where('workflow_name',$type)->pluck('id')->first();
            if($map_id){
                $header_id = Controller::model('Workflow_TransitionHeader','Workflow')::where('workflow_map_id',$map_id)->where('document_id',$id)->where('status','Active')->latest()->pluck('id')->first();
                if($header_id){
                    $w_id = Controller::model('Workflow_TransitionLine','Workflow')::where('workflow_transition_header',$header_id)->where('status','Pending')->where('emp_id',$emp?->id)->where('assign_date','!=',null)->where('approval_id',null)->latest()->pluck('id')->first();
                    if($w_id){
                        return base64_encode(convert_uuencode($w_id));
                    }
                }
            }
        }
        return null;
    }

    public static function getworkflow_url($data,$ref_id,$id){
        $url = route('workflow-request.list');;
        switch($data?->workflow_name){
            case 'Purchase Request':
                $url = route('pr-view', ['id' => encrypt($ref_id), 'w_id' => encrypt($id)]);
                break;

            case 'Quotation':
                $url = route('quotation-view', ['id' => encrypt($ref_id), 'w_id' => encrypt($id)]);
                break;

            case 'Purchase Order':
                $url = route('po-view', ['id' => encrypt($ref_id), 'w_id' => encrypt($id)]);
                break;

            case 'GRN':
                $url = route('grn-view', ['id' => encrypt($ref_id), 'w_id' => encrypt($id)]);
                break;

            case 'Invoice':
                $url = route('invoice-view', ['id' => encrypt($ref_id), 'w_id' => encrypt($id)]);
                break;
            default:
                $url = route('workflow-request.list');
                break;
        }
        return $url;
    }

    public static function wf_step_validation_check($step_id)
    {
        $data = Controller::model('WorkflowSteps','Workflow')::with('wf_assignment','conditions')->where('status','Active')->find($step_id);
        if($data?->condition != "Line Department"){
            if($data?->condition == "Conditional" && !$data?->substep_type){
                if(count($data?->conditions) <= 0){
                    return [false,'No Condition is defined for step'.$data?->step_name];
                }
            }
            if($data?->wf_assignment && count($data?->wf_assignment) <= 0){
                return [false,'No Assignment found for step'.$data?->step_name];
            }
            return [true,''];
        }
        return [true,''];
    }

    public static function wf_setup_hierarchy($id)
    {
        $setup = Controller::model('WorkflowSetup','Workflow')::find($id);
        $next_ids = [];
        $next_step_ids = Controller::model('WorkflowSteps','Workflow')::where('status','Active')->where('next_step_id','!=',null)->where('workflow_setup_id', $id)->pluck('next_step_id')->toArray();
        $step = Controller::model('WorkflowSteps','Workflow')::where('status','Active')->where('workflow_setup_id', $id)->get();
        $fisrt = Controller::model('WorkflowSteps','Workflow')::where('status','Active')->where('workflow_setup_id', $id)->first();
        $next_step_ids[] = $fisrt?->id;
        foreach($step as $stp){
            if(!($stp?->condition == 'Conditional' && $stp?->substep_type)){
                $next_ids[] = $stp?->id;
            }
        }
        $remaining_ids = array_diff($next_ids,$next_step_ids);
        if(count($remaining_ids)>0){
            $step_name = Controller::model('WorkflowSteps','Workflow')::where('status','Active')->whereIn('id',$remaining_ids)->pluck('step_name')->toArray();
            return [false,'Wofkflow steps '.implode(" , ",$step_name).' does not to be reached in any condition'];
        }
        else{
            return [true,''];
        }
    }


    public static function wf_setup_validate($id)
    {
        $setup = Controller::model('WorkflowSetup','Workflow')::find($id);
        $step = Controller::model('WorkflowSteps','Workflow')::where('status','Active')->where('workflow_setup_id', $id)->get();
        $hierarchy = self::wf_setup_hierarchy($id);
        if(!$hierarchy[0]){
            return [false,$hierarchy[1]];
        }
        foreach($step as $stp){
            $check = self::wf_step_validation_check($stp?->id);
            if(!$check[0]){
                return [false,$check[1]];
            }
        }
        return [true,'Successfully Validated !'];
    }

    public static function workflow_button($workflow_id)
    {
        $html = '';
        if($workflow_id){
            $w_id ="'$workflow_id'";
            $html = '<button title="Wworkflow" type="button" id="WorkflowButton" data-dropdown-toggle="Workflow"
                class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
                <i class="ri-list-check-3 text-teal-500 font-semibold"></i> Workflow </button>
            <div id="Workflow" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-md shadow-sm ">
                <ul class="py-2 w-[200px] text-sm text-gray-700" aria-labelledby="WorkflowButton">
                    <li> <a href="#" onclick="approve_modal('.$w_id.')" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-check-double-line"></i> Approve</a> </li>
                    <li> <a href="#" onclick="reject_modal('.$w_id.')" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-close-circle-line"></i> Reject</a> </li>
                    <li> <a href="#" onclick="change_request_modal('.$w_id.')" class="block px-4 py-2 hover:bg-gray-100"> <i class="ri-exchange-line"></i>
                        Change Request</a> </li>
                </ul>
            </div>';
        }
        return $html;
    }




}
