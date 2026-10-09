<?php

namespace  Modules\Workflow\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WorkflowRequestRepository
{
    public function yajraList()
    { 
        $login_user = Controller::user_id();
        $emp_id = Controller::helper('ApplyWorkflowHelper')::usertoemp($login_user);                
        $companyId = Controller::loginandcompany(); 
        $header_id = Controller::model('Workflow_TransitionHeader','Workflow')::where('status','Active')->pluck('id')->toArray();
        return Controller::model('Workflow_TransitionLine','Workflow')::with('requesterName','emp_name','workflowName','mapName')->where('emp_id',$emp_id)->where('status','Pending')->whereNotNull('assign_date')->whereIn('workflow_transition_header', $header_id)->get();         
    }
     
    public function approve_Workflow($action_id, $status, $comment)
    {
        $cmp_and_user = Controller::loginandcompany();
        $id = decrypt($action_id);
        $data =  Controller::model('Workflow_TransitionLine','Workflow')::find($id);
        $head_data = Controller::model('Workflow_TransitionHeader','Workflow')::find($data->workflow_transition_header);
        
        if ($head_data->status == 'Active') {
            $result = Controller::helper('WorkflowHelper')::updateStatus_line($id, $comment, $status);
            if ($result) {
                $line_data = Controller::model('Workflow_TransitionLine','Workflow')::with('mapName', 'emp_name')->find($result);
                if ($status == "Approved") {
                    $wf_step = Controller::model('WorkflowSteps','Workflow')::find($line_data?->workflow_steps_id);       
                    $no_of_approver = $wf_step?->no_of_approver ?? 1;
                    if($wf_step?->condition =="Employee" || $wf_step?->condition =="Conditional" || $wf_step?->condition =="Tender Committee Member"){                        
                        $approve_count = Controller::model('Workflow_TransitionLine','Workflow')::where('workflow_transition_header', $line_data->workflow_transition_header)->where('status', 'Approved')->where('workflow_steps_id', $line_data->workflow_steps_id)->count();                        
                        if($approve_count >= $no_of_approver){
                            $same_step = Controller::model('Workflow_TransitionLine','Workflow')::with('emp_name')->where('workflow_transition_header', $line_data->workflow_transition_header)->where('status', 'Pending')->where('workflow_steps_id', $line_data->workflow_steps_id)->get();
                            if (count($same_step) > 0) {
                                foreach ($same_step as $data) { 
                                    $data->reason = 'Approved by ' . $line_data?->emp_name?->name;
                                    $data->date = $line_data->date;
                                    $data->approval_id = $line_data->approval_id;
                                    $data->status = 'Approved';
                                    $data->save();                                    
                                }
                            } 
                        } 
                    }
                    
                    $next_rec = Controller::model('Workflow_TransitionLine','Workflow')::with('emp_name', 'step_name')->where('workflow_transition_header', $line_data->workflow_transition_header)->where('status', 'Pending')->first();                    
                    if ($next_rec) {
                        if ($next_rec?->step_name?->condition == "Employee" || $next_rec?->step_name?->condition == "Conditional" || $next_rec?->step_name?->condition == "Tender Committee Member") {
                            $next_all = Controller::model('Workflow_TransitionLine','Workflow')::with('emp_name','mapName')->where('workflow_transition_header', $next_rec->workflow_transition_header)->where('workflow_steps_id', $next_rec->workflow_steps_id)->where('status', 'Pending')->get();
                            foreach ($next_all as $next_dat) {
                                $next_dat->assign_date = date('Y-m-d');
                                $next_dat->save(); 

                                $cmp_emp = Controller::helper('ApplyWorkflowHelper')::email_by_empid($next_dat->emp_id);
                                $requester_data = Controller::helper('ApplyWorkflowHelper')::email_by_empid($next_dat->requester_id);  

                                $email_notification = Controller::model('WorkflowSteps','Workflow')::where('id',$wf_step?->id)->pluck('email_notification')->first();
                                if($email_notification == "Yes"){                                    
                                    $email_mstr = masterParentSingleData('EmailMaster','name','Workflow Task Assignment'); 
                                    if($cmp_emp[0]){ 
                                        $mail = Controller::helper('ApplyWorkflowHelper')::email_variables_wf_assign($email_mstr->id,[$cmp_emp[1],$next_dat->document_code,$next_dat->url,$next_dat?->mapName?->workflow_name,$requester_data[1],$next_dat->assign_date,$next_dat->ref_code]);                
                                        Mail::to($cmp_emp[0])->send(new AllMail($mail[0], $mail[1]));
                                    }
                                }
                            }
                        } else {
                            $next_rec->assign_date = date('Y-m-d');
                            $next_rec->save();

                            $cmp_emp = Controller::helper('ApplyWorkflowHelper')::email_by_empid($next_rec->emp_id);
                            $requester_data = Controller::helper('ApplyWorkflowHelper')::email_by_empid($next_rec->requester_id);  

                            $email_notification = Controller::model('WorkflowSteps','Workflow')::where('id',$wf_step?->id)->pluck('email_notification')->first();
                            if($email_notification == "Yes"){
                                $email_mstr = masterParentSingleData('EmailMaster','name','Workflow Task Assignment');  
                                if($cmp_emp[0]){
                                    $mail = Controller::helper('ApplyWorkflowHelper')::email_variables_wf_assign($email_mstr->id,[$cmp_emp[1],$next_rec->document_code,$next_rec->url,$next_rec?->mapName?->workflow_name,$requester_data[1],$next_rec->assign_date,$next_rec->ref_code]);                
                                    Mail::to($cmp_emp[0])->send(new AllMail($mail[0], $mail[1]));
                                }
                            }

                        }
                        return ['Assigned', $line_data?->mapName?->workflow_name, $line_data?->document_id];
                    } else {
                        $header_data = Controller::model('Workflow_TransitionHeader','Workflow')::find($line_data->workflow_transition_header);
                        $header_data->status = 'Completed';
                        $header_data->save();
                        return ['Approved', $line_data?->mapName?->workflow_name, $line_data?->document_id,$header_data];
                    }

                 // approval
                    $mod = Controller::model('WorkflowEventHistory','Workflow');

                    $event_record = new $mod();
                    $event_record->company_id = $cmp_and_user[1];
                    $event_record->type = $line_data?->mapName?->workflow_name;
                    $event_record->doc_id = $line_data->document_id;
                    $event_record->statement = '<b>' . $line_data->ref_code . $line_data->id . '</b> ' . $status . ' by ' . $line_data?->emp_name?->name .' with comment -'.$comment;
                    $event_record->status = $status;
                    $event_record->event_date = date('Y-m-d');
                    $event_record->created_by = $cmp_and_user[0];
                    $event_record->updated_by = $cmp_and_user[0];
                    $event_record->save();

                }

                if ($status == "Rejected" || $status == "Change Request") {
                    $next_records = Controller::model('Workflow_TransitionLine','Workflow')::where('workflow_transition_header', $line_data->workflow_transition_header)->where('status', 'Pending')->get();
                    if (count($next_records) > 0) {
                        foreach ($next_records as $next_record) {
                            $next_record->delete();
                        }
                    }

                    $header_data = Controller::model('Workflow_TransitionHeader','Workflow')::find($line_data->workflow_transition_header);
                    $header_data->status = 'Completed';
                    $header_data->save();

                    //event-history
                    $mod = Controller::model('WorkflowEventHistory','Workflow');

                    $event_record = new $mod();
                    $event_record->company_id = $cmp_and_user[1];
                    $event_record->type = $line_data?->mapName?->workflow_name;
                    $event_record->doc_id = $line_data->document_id;
                    $event_record->statement = '<b>' . $line_data->ref_code . $line_data->id . '</b> ' . $status . ' by ' . $line_data?->emp_name?->name.' with comment -'.$comment;
                    $event_record->status = $status;
                    $event_record->event_date = date('Y-m-d');
                    $event_record->created_by = $cmp_and_user[0];
                    $event_record->updated_by = $cmp_and_user[0];
                    $event_record->save();
                    return [$status, $line_data?->mapName?->workflow_name, $line_data->document_id];
                }
            } else {
                return [null,null,null];
            }
        }
        else
            return [null,null,null];
    }
  

}

