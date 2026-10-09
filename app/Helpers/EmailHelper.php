<?php
namespace App\Helpers;
use App\Http\Controllers\Controller;
use log;
use App\Mail\AllMail;
use Illuminate\Support\Facades\Mail;
class EmailHelper
{
    public static function email_template_id($name)
    {
        $email_mstr = Controller::model('MasterParent','DynamicMaster')::where('name',$name)
        ->whereHas('masterType', function ($query) {
            $query->where('name', "EmailMaster");
        })->first();
        return $email_mstr?->id;
    }

    public static function company_name($cpm_array = null)
    {
        $all_cmp = '';
        if($cpm_array && count($cpm_array)>0){
            foreach($cpm_array as $cmp){
                $cmpny = Controller::model('Company','DynamicMaster')::select('name')->find($cmp);
                if($all_cmp){
                    $all_cmp = $all_cmp .' , '. $cmpny?->name;
                }
                else{
                    $all_cmp = $cmpny?->name;
                }
            }
        }
        return $all_cmp;
    }

    public static function mail_send($email,$subject,$containts,$cc_bcc=null,$email_id=null,$bcc_emails=null)
    {
        if($email){
            if($bcc_emails && count($bcc_emails)>0 && $cc_bcc){
                if($email_id){
                    Mail::to($email_id)->bcc($bcc_emails)->send(new AllMail($subject, $containts));
                }
                else{
                    Mail::bcc($bcc_emails)->send(new AllMail($subject, $containts));
                }
            }
            else if($cc_bcc && $email_id){
                Mail::to($email)->$cc_bcc($email_id)->send(new AllMail($subject, $containts));
            }
            else{
                Mail::to($email)->send(new AllMail($subject, $containts));
                // Mail::to($email)->queue(new AllMail($subject, $contents));
            }
        }

    }

    public static function email_variables_replace($template_id,$var_array)
    {

        $email_temp = Controller::model('EmailTemplate','DynamicMaster')::where('email_master_id',$template_id)->first();
        $variables = explode(",",$email_temp?->veriable);

        if(count($variables) != count($var_array)){
            return [false,"Number of variables not matched"];
        }
        $subject = $email_temp?->subject;
        $result = null;
        preg_match_all('/\[[^\]]+\]/', $subject, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $placeholder) {
                $index = array_search($placeholder, $variables);
                if ($index !== false && isset($var_array[$index])) {
                    $subject = str_replace($placeholder, $var_array[$index], $subject);
                }
            }
        }
        $contents = str_replace($variables, $var_array, $email_temp->content);
        return [$subject,$contents];
    }
public static function email_variables_loginotp($template_id,$var_array)
    {
        $email_temp = Controller::model('EmailTemplate','DynamicMaster')::where('email_master_id',$template_id)->first();
        $variables = explode(",",$email_temp->veriable);
        $org_var = [];
        $replaced_var = [];
        $postfix_subject = '';
        foreach($variables as $var){
            switch($var){
                case "[OTP]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[0];
                    break;

                case "[REQUESTER_NAME]":
                    $org_var[] = $var;
                    $replaced_var[] = $var_array[1];
                    break;
            }
        }
        $contents = str_replace($org_var, $replaced_var, $email_temp->content);
        return [$email_temp?->subject,$contents];
    }




}
