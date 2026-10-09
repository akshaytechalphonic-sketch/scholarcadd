<?php

namespace Modules\Workflow\Helpers;
use App\Http\Controllers\Controller;
use App\Mail\AllMail;
use Mail;
use Illuminate\Support\Facades\Auth;
class ApplyWorkflowHelper
{
    public static function usertoemp($user_id)
    {

        $model_id = Controller::model('User')::where('Status','Active')->where('id',$user_id)->pluck('emp_id')->first();
        if(!$model_id){
            return null;
        }

        $emp_id = Controller::model('Employee','DynamicMaster')::where('Status','Active')->where('id',$model_id)->pluck('id')->first();
        if(!$emp_id){
            return null;
        }
        return $emp_id;
    }
    public static function check_emp_active($model,$model_id)
    {
        if($model == "User"){
            $model_id = Controller::model($model)::where('Status','Active')->where('id',$model_id)->pluck('emp_id')->first();
            if(!$model_id){
                return null;
            }
        }
        $emp_id = Controller::model('Employee','DynamicMaster')::where('Status','Active')->where('id',$model_id)->pluck('id')->first();
        if(!$emp_id){
            return null;
        }
        return $emp_id;
    }
    public static function get_workflow_apply_on($type_of_doc, $ref_id, $req_code,$company_id)
    {
        $cmp_and_user = Controller::loginandcompany();
        $cmp_id = Controller::helper('CommonHelper')::workflow_type();

        if($cmp_id){
            $company_id = $cmp_id;
        }
        try{
           $user_id = Controller::user_id();
            $emp_id = self::check_emp_active('User',$user_id);
            $todayDate = date("Y-m-d");
            $map_id = Controller::model('WorkflowMap','Workflow')::where('workflow_name', $type_of_doc)->pluck('id')->first();

            $w_id = Controller::model('WorkflowSetup','Workflow')::where('workflow_map_id',$map_id)->ActiveOnly($company_id)->pluck('id')->first();

            if ($w_id && $map_id) {
                $valid = Controller::helper('WorkflowHelper','Workflow')::wf_setup_validate($w_id);
                if($valid[0]){
                    $step = Controller::model('WorkflowSteps','Workflow')::where('workflow_setup_id', $w_id)->ActiveOnly($company_id)->where('status', 'Active')->first();
                    if($step){
                        $model_header = Controller::model('Workflow_TransitionHeader','Workflow');
                        $new_record = new $model_header();
                        $new_record->ref_code = Controller::helper('NumberSequenceHelpers')::generateNumberSequence('Workflow_TransitionHeader', 'ref_code', 'WFT-XN-', 5);
                        $new_record->workflow_map_id = $map_id;
                        $new_record->workflow_setup_id = $w_id;
                        $new_record->document_id = $ref_id;
                        $new_record->document_code = $req_code;
                        $new_record->requester_id = $user_id;
                        $new_record->date = $todayDate;
                        $new_record->status = "Active";
                        $new_record->created_by = $user_id;
                        $new_record->updated_by = $user_id;
                        $new_record->company_id = $company_id;
                        $new_record->save();

                        $user = [];
                        $count = 1;

                        $rat_data = self::wf_step_apply_by_step_id_and_header($step?->id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code);
                        if($rat_data == "Success"){
                            $wname = Controller::model('WorkflowSetup','Workflow')::where('workflow_map_id',$map_id)->ActiveOnly($company_id)->first();
                            $user= Auth::user();
                            $mod = Controller::model('WorkflowEventHistory','Workflow');

                            $event_record = new $mod();
                            $event_record->company_id = $company_id;
                            $event_record->type = $wname?->workflow_name;
                            $event_record->doc_id = $ref_id;
                            $event_record->statement = '<b>' . $req_code . ' is submitted for approval </b> by '.$user?->name;
                            $event_record->status = 'Submitted';
                            $event_record->event_date = date('Y-m-d');
                            $event_record->created_by = $cmp_and_user[0];
                            $event_record->updated_by = $cmp_and_user[0];
                            $event_record->save();
                            return $rat_data;
                        }
                        else{
                            return $rat_data;
                        }

                    }
                    else{
                        return "No steps are defined in applied Workflow";
                    }
                }
                else{
                    return $valid[1];
                }
            }else{
                return "Workflow not Mapped";
            }

            return "Success";
        }catch(Exception $e){
            return "Something went wrong";
        }
    }

    public static function wf_step_apply_by_step_id_and_header($step_id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code,$pr_edit=null){
        $model_edit_pr = null;
        $edit_permission_for_pr = false;

        $user_id = Controller::user_id();
        $data = Controller::model('WorkflowSteps','Workflow')::find($step_id);
        $cmp_and_user = Controller::loginandcompany();
        $company_id = $cmp_and_user[1] ?? $data?->company_id;

        if ($data->condition == 'Other Department') {
            $dept_id = Controller::model('WorkflowAssignment','Workflow')::where('workflow_step_id', $data->id)->pluck('dept_id')->first();
            if($dept_id){
                $emp_id = Controller::model('Department','DynamicMaster')::where('id',$dept_id)->pluck('Company_employee_id')->first();
                if($emp_id){
                    $emp_id = self::check_emp_active('Emp',$emp_id);
                    if(!$emp_id){
                        return "The head employee of other department condition is not active";
                    }
                    else{
                        self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code ,null,$pr_edit);
                        $count++;

                        if($data?->next_step_id){
                            return self::wf_step_apply_by_step_id_and_header($data?->next_step_id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code);
                        }




                    }
                }
                else{
                    return "No Department head is assigned in the condition of Other department";
                }
            }
            else{
                return "No deprtment is found in the condition of Other department";
            }
        }

        else if ($data->condition == 'Line Department') {
            $emp_id = self::check_emp_active('User',$user_id);
            if($emp_id){
                $dept_id = Controller::model('Employee','DynamicMaster')::where('id',$emp_id)->pluck('department_id')->first();
                if($dept_id){
                    $emp_id = Controller::model('Department','DynamicMaster')::where('id',$dept_id)->pluck('Company_employee_id')->first();
                    if($emp_id){
                        $emp_id = self::check_emp_active('Emp',$emp_id);
                        if(!$emp_id){
                            return "The head employee of Line department condition is not active";
                        }
                    else{
                            self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code ,null,$pr_edit);
                            $count++;
                            if($data?->next_step_id){
                                return self::wf_step_apply_by_step_id_and_header($data?->next_step_id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code);
                            }
                        }
                    }
                    else{
                        return "No Department head is assigned in the condition of Line department";
                    }
                }
                else{
                    return "No deprtment is found in the condition of Line department";
                }
            }
            else{
                return "No Department is found in case of Line Department";
            }
        }

        else if ($data->condition == 'Employee') {
            $emps = Controller::model('WorkflowAssignment','Workflow')::where('workflow_step_id', $data->id)->get()->pluck('employee_id');
            foreach ($emps as $emp_id) {
                $emp_id = self::check_emp_active('Emp',$emp_id);
                if(!$emp_id){
                    return "The employee of Employee condition is not active";
                }
                else{
                    self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code ,null,$pr_edit);
                }
            }
            $count++;
            if($data?->next_step_id){
                return self::wf_step_apply_by_step_id_and_header($data?->next_step_id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code);
            }
        }

        else if ($data->condition == 'Line Manager') {
            $level_count = Controller::model('WorkflowAssignment','Workflow')::where('workflow_step_id', $data->id)->pluck('level')->first();
            $emp_id = self::check_emp_active('User',$user_id);
            if($emp_id){
                $emp_id = Controller::model('Employee','DynamicMaster')::where('id',$emp_id)->pluck('reportto_id')->first();
                if($emp_id){
                    while ($level_count > 0 && $emp_id) {
                        self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code, null,$pr_edit);
                        $count++;
                        $emp_id = Controller::model('Employee','DynamicMaster')::where('id',$emp_id)->pluck('reportto_id')->first();
                        $level_count--;
                    }
                    if($data?->next_step_id){
                        return self::wf_step_apply_by_step_id_and_header($data?->next_step_id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code);
                    }
                }
                else{
                    return "Reporting persion is not found in the condition of Line manager";
                }
            }
            else{
                return "Employee is not active in the condition of Line manager";
            }
        }

        else if ($data->condition == 'Conditional') {
            $wf_assignment = Controller::model('WorkflowSetup','Workflow')::where('workflow_map_id',$map_id)->with('apply_on')->ActiveOnly($company_id)->first();
            $cndtns = Controller::model('Workflow_Condition','Workflow')::where('workflow_step_id',$data?->id)->get();

            $cdn_all = Controller::model('WorkflowAssignment','Workflow')::where('workflow_step_id', $data->id)->get();
            if($cdn_all && count($cdn_all)>0){
                foreach($cdn_all as $cdns){
                    if($cdns?->employee_id){
                        $emp_id = self::check_emp_active('Emp',$cdns?->employee_id);
                        if(!$emp_id){
                            return "The employee of Employee condition is not active";
                        }
                        else{
                            self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code ,null,$pr_edit);
                        }
                    }
                    else if($cdns?->dept_id){
                        $emp_id = Controller::model('Department','DynamicMaster')::where('id',$cdns?->dept_id)->pluck('Company_employee_id')->first();
                        if($emp_id){
                            $emp_id = self::check_emp_active('Emp',$emp_id);
                            if(!$emp_id){
                                return "The head employee of other department condition is not active";
                            }
                            else{
                                self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code ,null,$pr_edit);
                                $count++;
                            }
                        }
                        else{
                            return "No Department head is assigned in the condition of Conditional department";
                        }
                    }
                    else if($cdns?->level){
                        $level_count = $cdns?->level;
                        $emp_id = self::check_emp_active('User',$user_id);
                        if($emp_id){
                            $emp_id = Controller::model('Employee','DynamicMaster')::where('id',$emp_id)->pluck('reportto_id')->first();
                            if($emp_id){
                                while ($level_count > 0 && $emp_id) {
                                    self::add_transition_line($company_id,$emp_id, $map_id, $w_id, $data->id, $new_record, $ref_id, $count, $req_code, null,$pr_edit);
                                    $count++;
                                    $emp_id = Controller::model('Employee','DynamicMaster')::where('id',$emp_id)->pluck('reportto_id')->first();
                                    $level_count--;
                                }
                            }
                            else{
                                return "Reporting persion is not found in the condition of Line manager";
                            }
                        }
                        else{
                            return "Employee is not active in the condition of Line manager";
                        }
                    }
                }
                $count++;

                $doc_data = null;
                if(count($cndtns)>0){
                    $sw = $wf_assignment?->apply_on?->workflow_name;
                    switch($sw){
                        case "Purchase Request";
                            $model_edit_pr = "PurchaseRequest";
                            $query = Controller::model('PurchaseRequest','PurchaseRequest')::query();
                            $columns = ['Amount'=>'total','Department'=>'department_id','Project'=>'project_id','Budget Amount'=>'budget_estimation','Is Emergency PR'=>'emergency_pr'];
                            break;

                        case "Quotation"; //'Amount'=>'total',
                            $query = Controller::model('Quotation','Quotation')::query();
                            $columns = ['Department'=>'department_id'];
                            break;

                        case "Purchase Order"; //,'Budget Amount'=>'budget_estimation'];
                            $query = Controller::model('PurchaseOrder','PurchaseOrder')::query();
                            $columns = ['Amount'=>'net_amount','Department'=>'department_id','Project'=>'project_id'];
                            break;

                        case "GRN";
                            $query = Controller::model('GRNHeader','GRN')::query();
                            $columns = ['Amount'=>'net_amount'];
                            break;

                        case "Invoice";
                            $query = Controller::model('InvoiceHeader','Invoice')::query();
                            $columns = ['Amount'=>'net_amount'];
                            break;


                    }

                    $query->where('id', $ref_id);
                    foreach($cndtns as $condn){
                        if($condn?->field){

                            if($condn?->field == "Department" || $condn?->field == "Project" || $condn?->field == "Envelope" || $condn?->field == "Vendor Group"){

                                $mod_type = $condn?->field;
                                $temp_model = "MasterParent";
                                $module = "DynamicMaster";

                                $pro_dept = Controller::model($temp_model,$module)::where('name',$condn?->condition_value)
                                ->whereHas('masterType', function ($query) use ($mod_type) {
                                    $query->where('name', $mod_type);
                                })
                                ->first();
                                if($pro_dept){
                                    $symbol = self::comperasion_text_to_symbol($condn?->condition);
                                    if($symbol[0]){
                                        if($symbol[1] != "Between"){
                                            if($model_edit_pr == "PurchaseRequest"){
                                                $only_pr = Controller::model('PurchaseRequest','PurchaseRequest')::select('id','department_id')->with('department')->find( $ref_id);
                                                //,$only_pr->department->id != $pro_dept?->id ||
                                                if($only_pr->department->name != "Procurement Department"){
                                                    $edit_permission_for_pr = true;
                                                }
                                            }
                                            $query->where($columns[$condn?->field],$symbol[1],$pro_dept?->id);
                                        }
                                        else{
                                            return $condn?->condition." Can not apply on ".$condn?->field;
                                        }
                                    }
                                    else{
                                        return "Symbol ".$condn?->condition." Not defined";
                                    }
                                }
                                else{
                                    return $condn?->field." ".$condn?->condition_value." Not found";
                                }
                            }


                            else if($condn?->field == "Amount" || $condn?->field == "Budget Amount" || $condn?->field == "Is Emergency PR"){
                                $symbol = self::comperasion_text_to_symbol($condn?->condition);
                                if($symbol[0]){
                                    if($symbol[1] == "Between"){

                                        $range = explode('--',$condn?->condition_value);
                                        if(count($range) == 2){
                                            if($range[0] < $range[1]){
                                                $query->whereBetween($columns[$condn?->field],  [$range[0],$range[1]]);//where($columns[$condn?->field],'>=',$range[0])->where($columns[$condn?->field],'<=',$range[1]);
                                            }
                                            else{
                                                return $condn?->condition_value." Range is not defined properly";
                                            }
                                        }
                                        else{
                                            return $condn?->condition_value." Is not suitable for value as In Between";
                                        }
                                    }
                                    else{
                                        $query->where($columns[$condn?->field],$symbol[1],$condn?->condition_value);
                                    }
                                }
                                else{
                                    return "Symbol ".$condn?->condition." Not defined";
                                }
                            }
                        }
                    }
                    $doc_data = $query->first();
                }
                if($doc_data){
                    $new_data = Controller::model('WorkflowSteps','Workflow')::where('workflow_setup_id',$data?->workflow_setup_id)->where('substep_type','True')->where('condition','Conditional')->where('step_name',$data?->step_name.'.1')->first();
                    $st = "True";
                }
                else{
                    $new_data = Controller::model('WorkflowSteps','Workflow')::where('workflow_setup_id',$data?->workflow_setup_id)->where('substep_type','False')->where('condition','Conditional')->where('step_name',$data?->step_name.'.2')->first();
                    $st = "False";
                }
                if($new_data){
                    return self::wf_step_apply_by_step_id_and_header($new_data?->id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code,$edit_permission_for_pr);
                }
                else if($data?->next_step_id){
                    return self::wf_step_apply_by_step_id_and_header($data?->next_step_id,$new_record,$map_id, $w_id, $ref_id, $count, $req_code,$edit_permission_for_pr);
                }

            }
            else{
                return "No assignment found in step ".$data?->step_name;
            }


        }

        return "Success";
    }

    public static function comperasion_text_to_symbol($text){
        if($text=="Equals to")
            return [true,'='];
        else if($text=="Less Than")
            return [true,'<'];
        else if($text=="Less Than Equals to")
            return [true,'<='];
        else if($text=="Greater Than")
            return [true,'>'];
        else if($text=="Greater Than Equals to")
            return [true,'>='];
        else if($text=="In Between")
            return [true,'Between'];
        else
            return [false,''];
    }

    public static function add_transition_line($company_id,$asgn_emp_id, $map_id, $setup_id, $steps_id, $header, $ref_id, $count, $req_code,$signature,$pr_edit)
    {
        $todayDate = date("Y-m-d");
        $delegated1 = Controller::model('WorkflowDeligation','Workflow')::where('emp_from', $asgn_emp_id)
            ->where('workflow_id', $map_id)
            ->where('status', 'Active')
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();
        $delegated2 = Controller::model('WorkflowDeligation','Workflow')::where('emp_from', $asgn_emp_id)
            ->where('workflow_id', null)
            ->where('status', 'Active')
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();

        if ($delegated1) {
            $deligated_emp_id = $delegated1->emp_to;
        } else if ($delegated2) {
            $deligated_emp_id = $delegated2->emp_to;
        } else {
            $deligated_emp_id = $asgn_emp_id;
        }


        $model = Controller::model('Workflow_TransitionLine','Workflow');
        $new_record = new $model();
        $new_record->workflow_transition_header = $header->id;
        $new_record->workflow_steps_id = $steps_id;
        $new_record->workflow_map_id = $map_id;
        $new_record->workflow_setup_id = $setup_id;
        $new_record->document_id = $ref_id;
        $new_record->document_code = $req_code;
        $new_record->emp_id = $deligated_emp_id;

        if ($count == 1) {
            $new_record->assign_date = $todayDate;
        }
        $new_record->created_by = Auth::user()?->id;
        $new_record->company_id = $company_id;
        $new_record->requester_id = Auth::user()?->id;
        $new_record->status = "Pending";
        $new_record->save();
        $new_record->ref_code = $header->ref_code.'-'.$new_record->id;
        $new_record->save();

        $url = route('dashboard');
        $map_data = Controller::model('WorkflowMap','Workflow')::find($map_id);

        switch($map_data?->workflow_name){
            case 'Purchase Request':
                if($pr_edit){
                    $url = route('pr-edit', ['id' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id)), 'type' => 'approval' ?? null]);
                }else{
                    $url = route('pr-view', ['id' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id))]);
                }
                break;

            case 'Quotation':
                $url = route('quotation-view', ['id' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id))]);
                break;

            case 'Purchase Order':
                $url = route('po-view', ['id' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id))]);
                break;

            case 'GRN':
                $url = route('grn-edit', ['id' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id))]);
                break;

            case 'Invoice':
                $url = route('invoice-edit', ['id' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id))]);
                break;



            case 'Quotation Reply':
                $url = route('compair.quotaion.view', ['compairid' => base64_encode(convert_uuencode($ref_id)), 'w_id' => base64_encode(convert_uuencode($new_record->id))]);
                break;



            default:
                $url = route('workflow-request.list');
                break;
        }

        $new_record->url = $url;
        $new_record->save();

        if ($count == 1) {
            $email_notification = Controller::model('WorkflowSteps','Workflow')::where('id',$steps_id)->pluck('email_notification')->first();
            if($email_notification == "Yes"){
                $cmp_emp = self::email_by_empid($deligated_emp_id);
                $u_name = Controller::user()?->name;
                $email_mstr = masterParentSingleData('EmailMaster','name','Workflow Task Assignment');
                if($cmp_emp[0]){
                    $mail = self::email_variables_wf_assign($email_mstr->id,[$cmp_emp[1],$req_code,$url,$map_data->workflow_name,$u_name,$todayDate,$new_record->ref_code]);
                    Mail::to($cmp_emp[0])->send(new AllMail($mail[0], $mail[1]));
                }
            }
        }
    }

    public static function email_by_empid($emp_id)
    {
        $emp_emp = Controller::model('Employee','DynamicMaster')::find($emp_id);
        return [$emp_emp?->email,$emp_emp?->name];
    }

    public static function email_variables_wf_assign($email_template_id,$var_array)
    {
        $email_temp = Controller::model('EmailTemplate','DynamicMaster')::where('email_master_id',$email_template_id)->first();
        $variables = explode(",",$email_temp->veriable);
        $org_var = [];
        $replaced_var = [];
        $postfix_subject = '';
        foreach($variables as $var){
            switch($var){
                case "[NAME]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[0];
                    break;

                case "[DOCUMENT_NO]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[1];
                    break;

                case "[URL]":
                    $org_var[] = $var;
                    $replaced_var[] = '<a href="'.$var_array[2].'"> Click Here </a>';
                    break;

                case "[DOCUMENT_TYPE]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[3];
                    break;

                case "[CREATED_BY]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[4];
                    break;

                case "[CREATED_ON]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[5];
                    break;

                case "[TASK_ID]":
                    $postfix_subject = str_replace('[TASK_ID]', $var_array[6], $email_temp?->subject);
                    break;
            }
        }
        $contents = str_replace($org_var, $replaced_var, $email_temp->content);

        return [$postfix_subject,$contents];
    }



}
