<?php

namespace Modules\SystemControl\Services;

use Modules\SystemControl\Repositories\SystemControlRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SystemControlService extends Controller
{
    protected SystemControlRepository $repository;

    public function __construct(SystemControlRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $company_id =$cmp_and_user[1];
        $all_date_formates = Controller::helper('CommonHelper')::all_date_formates();
        $time_zone = Controller::helper('CommonHelper')::timezones();
        $model = Controller::model('SystemControl','SystemControl');
        $data = $model::where('company_id',$company_id)->first();
        $doc_formate = explode(',',$data?->doc_format);

        $currencies = masterParentData('Currency', $company_id);
        $companies = Controller::model('Company','DynamicMaster')::where('status','Active')->get();
      
        return view('systemcontrol::right_sidebar',compact('data','companies','time_zone','currencies','all_date_formates','doc_formate'));
    }

    public function sidebar_store(Request $request)
    {
        try{
            Controller::db()::beginTransaction();
            $cmp_and_user = Controller::loginandcompany();
            $company_id =$cmp_and_user[1];
            if(!$company_id){
                return back()->with(['error'=> "You don not have any company"]);
            }
            $model = Controller::model('SystemControl','SystemControl');
            $data = $model::where('company_id',$company_id)->first();
            if(!$data){
                $data = new $model();
                $data->company_id = $company_id;
            }

            $doc_formate = '';
            if($request->doc_format && count($request->doc_format)>0){
                $doc_formate = implode(',',$request->doc_format);
            }
            $data->dark_mode = $request->dark_mode ? true : false;
            $data->rtl = $request->rtl ? true : false;
            $data->def_language = $request->def_language ?? 'EN';
            $data->date_format = $request->date_format;
            $data->time_format = $request->time_format ?? 12;
            $data->time_zone = $request->time_zone;
            $data->default_currency_id = $request->default_currency_id ?? null;
            $data->currency_symbol = $request->currency_symbol ?? 'code';
            $data->enable_system_notification = $request->enable_system_notification ? true : false;
            $data->enable_email_notification = $request->enable_email_notification ? true : false;
            $data->doc_format = $doc_formate;
            $data->brand_name = $request->brand_name;
            $data->doc_max_size = $request->doc_max_size;
            $data->two_fa_enable_employee = $request->two_fa_enable_employee ? "Yes" : "No";
            $data->enable_google_auth = $request->enable_google_auth ? "Yes" : "No";
            $data->enable_copy_paste = $request->enable_copy_paste ? "Yes" : "No";


            $data->mail_mailer = $request->mail_mailer;
            $data->mail_host = $request->mail_host;
            $data->mail_port = $request->mail_port;
            $data->mail_user_name = $request->mail_user_name;
            $data->mail_password = $request->mail_password;
            $data->mail_encryption = $request->mail_encryption;
            $data->mail_from_name = $request->mail_from_name;
            $data->mail_from_address = $request->mail_from_address;

            $data->auto_release_enable = $request->auto_release_time ? true : false;
            $data->auto_release_time = $request->auto_release_time;
            $data->password_expiry_enable = $request->password_expiry_days ? true : false;
            $data->password_expiry_days = $request->password_expiry_days;
            $data->default_company_enable = $request->default_company ? true : false;
            $data->default_company = $request->default_company;
            $data->default_period_id = $request->default_period_id;
            $data->invoice_cancle_allow = $request->invoice_cancle_allow ? "Yes" : "No";
            $data->grn_cancle_allow = $request->grn_cancle_allow ? "Yes" : "No";
            $data->workflow_type = $request->workflow_type;
            $data->numbersequence_type = $request->numbersequence_type;

            $data->save();

            if($request->light_logo){
                $image = Controller::helper('CommonHelper')::folder_name($data->id,'SystemControl','Light Logo',$request->light_logo,5,'image','SystemControl');
                if($image){
                    $data->light_logo = $image[1];
                }
            }
            if($request->small_logo){
                $image = Controller::helper('CommonHelper')::folder_name($data->id,'SystemControl','Small Logo',$request->small_logo,5,'image','SystemControl');
                if($image){
                    $data->small_logo = $image[1];
                }
            }
            $data->save();

            Controller::db()::commit();

            return back()->with(['success'=> "Saved"]);
        }catch (\Exception $e) {
            dd($e);
            Controller::db()::rollback();
            catch_log($e, "Quotation Accept/Reject");
        }


    }

}
