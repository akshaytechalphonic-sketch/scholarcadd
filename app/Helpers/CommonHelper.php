<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
// use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Models\Vt_Docouments;
// use Carbon\Carbon;
// use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class CommonHelper
{
    public static function folder_name($doc_id, $doc_model, $document_type, $uploadedImage, $size, $file_type,$module_name = null)
    {
        $extension = $uploadedImage->getClientOriginalExtension();
        $extension = strtolower($extension);
        if ($file_type == "image" || $file_type == "images")
            $allowedExtensions = ['jpeg', 'png', 'jpg'];
        else if ($file_type == "document")
            $allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'xls'];
        else if ($file_type == "image_document")
            $allowedExtensions = ['jpeg', 'png', 'jpg', 'pdf', 'doc', 'docx', 'xlsx', 'xls'];
        
        if (in_array($extension, $allowedExtensions) && $uploadedImage->getSize() / 1048576 <= $size) {

            $doc_data = Controller::model($doc_model,$module_name)::where('id', $doc_id)->first();
            if ($doc_data) {
                $company_id = $doc_data->company_id;
                $doc_code = base64_encode(convert_uuencode($doc_id));
            } else {
                return false;
            }
            $company = Controller::model('Company','DynamicMaster')::where('id', $company_id)->first();
            $company_name = $company ? $company->name : 'Default_Company';
            $company_name = ($company_name);
            $doc_type = ($doc_model);
            $document_type = ($document_type);
            $folder_path = "documents/{$company_name}/{$doc_model}/{$doc_code}/{$document_type}";

            $imageName = time() . rand(100, 999) . '.' . $extension;
            $image_url = "{$folder_path}/{$imageName}";

            Storage::disk('public')->makeDirectory($folder_path);

            Storage::disk('public')->putFileAs($folder_path, $uploadedImage, $imageName);

            return [$imageName, $image_url];
        } else {
            return false;
        }
    }

    public static function generate_password($length=12)
    {

        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '@#$%^&*!';
        $randomString = '';
        $randomString .= $upper[rand(0, strlen($upper) - 1)];
        $randomString .= $lower[rand(0, strlen($lower) - 1)];
        $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        $randomString .= $specialChars[rand(0, strlen($specialChars) - 1)];
        $generatedParts = [];
        while (strlen($randomString) < $length) {
            $alphanumeric = substr(str_shuffle($upper . $lower . $numbers), 0, 3);
            $special = $specialChars[rand(0, strlen($specialChars) - 1)];
            $randomString .= $alphanumeric . $special;
            $generatedParts[] = $alphanumeric . $special;
            if (strlen($randomString) >= $length) {
                break;
            }
        }
        $invalidPatterns = ['1234', 'abcd', 'qwerty', 'asdf', 'zxcv'];
        foreach ($invalidPatterns as $pattern) {
            if (strpos($randomString, $pattern) !== false) {
                return generateRandomString($length);
            }
        }
        $randomString = str_shuffle($randomString);
        return substr($randomString, 0, $length);
    }

    public static function create_user($name, $email, $emp_id, $password, $type, $img = null)
    {

        $model = Controller::model('User');
        $data = new $model();
        $data->name = $name;
        $data->email = $email;
        $data->emp_id = $emp_id;
        $data->password = Hash::make($password);
        $data->type = $type;
        $data->profile_img = $img;
        $data->status = 'Active';
        $data->save();
        return $data;
    }

    public static function companies_by_doc_id($user_type_id, $id)
    {
        $company = Controller::model('UserCompany','DynamicMaster')::where($user_type_id, $id)->where('status', 'Active')->get()->pluck('company_id')->toArray();
        return array_unique($company);
    }

    public static function workflow_type()
    {
        $data = Controller::model('SystemAdminSetup')::pluck('workflow_type')->first();

        if ($data == "Consolidated") {
            $company_id = Controller::model('Company')::where('default_company', 'Yes')->pluck('id')->first();
            if (!$company_id) {
                return 1;  //Consolidated and no company is default
            } else {
                return $company_id;  // Consolidated and company id which is default
            }
        } else {
            return null;//Company wise
        }
    }

    public static function user_name_emp_id()
    {
        $user_id = Controller::loginandcompany();
        $data = Controller::model('Employee','DynamicMaster')::with('department')->where('user_id',$user_id[0])->first();
        return $data;

    }

    public static function leftbar_detaild()
    {
        $user = Auth::user();
        $name = $user?->name;
        $short_name = '';
        if ($name) {
            $parts = explode(' ', trim($name));
            foreach ($parts as $part) {
                if (!empty($part)) {
                    $short_name .= strtoupper($part[0]);
                }
            }
        }

        if ($user?->status == "Active") {
            if ($user?->type == 'User' || $user?->type == 'Employee') {
                $type = "Emp";
            } else if ($user?->type == 'Approved Vendor') {
                $type = "Vendor";
            } else {
                $type = "Incomplete Vendor";
            }
            if ($user?->profile_img) {
                $img = $user?->profile_img;
            } else {
                $img = $user?->profile_img;
            }
            return [$type, $user?->name, $img,$user?->company?->name,$short_name, $user?->email];
        }
        return ['', '', '','', '',''];
    }

     public static function sequence_type()
    {
        $data = Controller::model('SystemControl','DynamicMaster')::pluck('numbersequence_type')->first();
        if ($data == "Consolidated") {
            $company_id = Controller::model('Company','DynamicMaster')::where('default_company', 'Yes')->pluck('id')->first();
            if (!$company_id) {
                return 1;  //Consolidated and no company is default
            } else {
                return $company_id;  // Consolidated and company id which is default
            }
        } else {
            return null;//Company wise
        }
    }

    public static function get_all_company(){
        return Controller::model('Company','DynamicMaster')::select('id','code','name','status','default_company')->where('status','Active')->get();
    }

    public static function check_cr_expiry_by_vendor_id($vendor_id){
        $data = Controller::model('VendorDocument','Vendor')::where('vendor_id',$vendor_id)->where('name','Commercial Registration')->pluck('expiry_date')->first();
        if($data && $data >= now()){
            return false;
        }
        return true;
    }

    public static function get_all_active_vendor($company_ids){
        $all_vendors = [];
        if(gettype($company_ids) == "array" || gettype($company_ids) == "Array"){
            if($company_ids && count($company_ids)>0){
                foreach($company_ids as $company_id){
                    $vendors = [];
                    $all_vdr = Controller::model('Seller','Seller')::where('company_id',$company_id)
                    ->select('id','code','approved_code','name','email','cr_number','company_id')
                    ->get();
                    foreach($all_vdr as $vdr){
                        if(!self::check_cr_expiry_by_vendor_id($vdr?->id)){
                            $vendors[] = $vdr;
                        }
                    }
                    $all_vendors[] = $vendors;
                }
            }
        }
        else{
            $all_vdr = Controller::model('Seller','Seller')::where('company_id',$company_ids)->select('id','code','approved_code','name','email','cr_number','company_id')->get();
            $vendors = [];
            foreach($all_vdr as $vdr){
                if(!self::check_cr_expiry_by_vendor_id($vdr?->id)){
                    $vendors[] = $vdr;
                }
            }
            $all_vendors = $vendors;
        }
        return $all_vendors;
    }
    public static function current_date()
    {
        return now();
    }

     public static function get_all_active_customers($company_ids){
        $all_customers = [];
        if(gettype($company_ids) == "array" || gettype($company_ids) == "Array"){
            if($company_ids && count($company_ids)>0){
                foreach($company_ids as $company_id){
                    $customers = [];
                    $all_cust = Controller::model('Customer','Customer')::where('company_id',$company_id)
                    ->select('id','name','email','phone_no','status','company_id')
                    ->get();
                    // foreach($all_cust as $cust){
                    //     if(!self::check_cr_expiry_by_vendor_id($cust?->id)){
                    //         $customers[] = $cust;
                    //     }
                    // }
                    $all_customers[] = $all_cust;
                }
            }
        }
        else{
            $all_cust = Controller::model('Customer','Customer')::where('company_id',$company_ids)->select('id','name','email','phone_no','status','company_id')->get();
            $customers = [];
            // foreach($all_cust as $cust){
            //     if(!self::check_cr_expiry_by_vendor_id($cust?->id)){
            //         $customers[] = $cust;
            //     }
            // }
            $all_customers = $all_cust;
        }

        return $all_customers;
    }

    public static function getactiveemployeeexcept($id)
    {
        $cmp_id = Auth::user()->company_id;
        $employees = Controller::model('Employee','DynamicMaster')::ActiveOnly($cmp_id)->whereNot('id', $id)->get();
        return $employees;
    }
    public static function workflow_approval_button($w_id)
    {
        $html = '';
        if($w_id){
            $approve_modal = "'approve-sidebar'";
            $reject_modal = "'reject-sidebar'";
            $chngreq_modal = "'change-request_modal-sidebar'";
            $w_id = "'$w_id'";
            $html = '<div class="relative inline-block">
                <button  data-dropdown-button type="button" class="inline-flex hover-scale items-center px-2 py-2 transition duration-300 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 border-l-[1px]">
                    <i class="ri-function-line"></i> Action </button>
                <div data-dropdown-content
                    class="absolute hidden right-0 mt-2 min-w-[200px] bg-white border border-gray-200 rounded-md shadow-lg z-50 overflow-hidden animate-fade-in">
                    <div class="flex flex-col text-sm text-gray-700">
                        <button type="button" onclick="view_model('.$approve_modal.','.$w_id.')"
                            class="w-full text-left hover:bg-green-100 hover:text-green-700 text-gray-600 px-4 py-2 transition">
                            <i class="ri-check-line mr-2"></i> Approve
                        </button>
                        <button type="button" onclick="view_model('.$reject_modal.','.$w_id.')"
                            class="w-full text-left hover:bg-red-100 hover:text-red-600 text-gray-600 px-4 py-2 transition">
                            <i class="ri-close-line mr-2"></i> Reject
                        </button>
                        <button type="button" onclick="view_model('.$chngreq_modal.','.$w_id.')"
                            class="w-full text-left hover:bg-blue-100 hover:text-blue-700 text-gray-600 px-4 py-2 transition">
                            <i class="ri-list-check mr-2"></i> Change Request
                        </button>
                    </div>
                </div>
            </div>';
        }
        return $html;
    }


    public static function CheckUserCompany($vendor_id)
    {
        try{
            $vendor_data = Controller::model('Vendor','Vendor')::where('id',$vendor_id)->where('status','Approved')->first();
            if($vendor_data){
                return ['data' => $vendor_data,'message'=>'common.success'];
            }
            else{
                return ['data' => '','message'=>'Vendor is not active for this company'];
            }

        }catch (Exception $e) {
            return ['data'=>'','message'=>$e->getMessage()];
        }

    }
     public static function timezones()
    {

        $time_zone = [
            ['zone' => 'Pacific/Kiritimati','value' => '+14:00'],
            ['zone' => 'Pacific/Kanton','value' => '+13:00'],
            ['zone' => 'Pacific/Chatham','value' => '+12:45'],
            ['zone' => 'Antarctica/McMurdo','value' => '+12:00'],
            ['zone' => 'Pacific/Kosrae','value' => '+11:00'],
            ['zone' => 'Australia/Lord_Howe','value' => '+10:30'],
            ['zone' => 'Antarctica/DumontDUrville','value' => '+10:00'],
            ['zone' => 'Australia/Adelaide','value' => '+09:30'],
            ['zone' => 'Asia/Jayapura','value' => '+09:00'],
            ['zone' => 'Australia/Eucla','value' => '+08:45'],
            ['zone' => 'Antarctica/Casey','value' => '+08:00'],
            ['zone' => 'Antarctica/Davis','value' => '+07:00'],
            ['zone' => 'Indian/Cocos','value' => '+06:30'],
            ['zone' => 'Asia/Dhaka','value' => '+06:00'],
            ['zone' => 'Asia/Kathmandu','value' => '+05:45'],
            ['zone' => 'Asia/Kolkata','value' => '+05:30'],
            ['zone' => 'Antarctica/Mawson','value' => '+05:00'],
            ['zone' => 'Asia/Kabul','value' => '+04:30'],
            ['zone' => 'Asia/Muscat','value' => '+04:00'],
            ['zone' => 'Asia/Tehran','value' => '+03:30'],
            ['zone' => 'Antarctica/Syowa','value' => '+03:00'],
            ['zone' => 'Europe/Tirane','value' => '+02:00'],
            ['zone' => 'Africa/Algiers','value' => '+01:00'],

            ['zone' => 'Atlantic/Cape_Verde','value' => '-01:00'],
            ['zone' => 'America/St_Johns','value' => '-02:30'],
            ['zone' => 'Antarctica/Palmer','value' => '-03:00'],
            ['zone' => 'America/Anguilla','value' => '-04:00'],
            ['zone' => 'America/Eirunepe','value' => '-05:00'],
            ['zone' => 'America/Belize','value' => '-06:00'],
            ['zone' => 'America/Creston','value' => '-07:00'],
            ['zone' => 'Pacific/Pitcairn','value' => '-08:00'],
            ['zone' => 'Pacific/Gambier','value' => '-09:00'],
            ['zone' => 'Pacific/Marquesas','value' => '-09:30'],
            ['zone' => 'Pacific/Rarotonga','value' => '-10:00'],
            ['zone' => 'Pacific/Pago_Pago','value' => '-11:00']
        ];
        return $time_zone;
    }

    public static function all_date_formates()
    {
        $time_frmt = [
            ['format' => 'y-m-d','format_view'=>'YY-MM-DD'],
            ['format' => 'd-m-Y','format_view'=>'DD-MM-YYYY'],
            ['format' => 'd-m-y','format_view'=>'DD-MM-YY'],
            ['format' => 'm-d-Y','format_view'=>'MM-DD-YYYY'],
            ['format' => 'm-d-y','format_view'=>'MM-DD-YY'],
            ['format' => 'Y-m-d','format_view'=>'YYYY-MM-DD'],
            ['format' => 'y/m/d','format_view'=>'YY/MM/DD'],
            ['format' => 'd/m/Y','format_view'=>'DD/MM/YYYY'],
            ['format' => 'd/m/y','format_view'=>'DD/MM/YY'],
            ['format' => 'm/d/Y','format_view'=>'DD/MM/YYYY'],
            ['format' => 'm/d/Y','format_view'=>'YYYY-MM-DD'],
            ['format' => 'Y/m/d','format_view'=>'YYYY/MM/DD'],
            ['format' => 'd M Y','format_view'=>'DD MM YYYY'],
            ['format' => 'j F Y','format_view'=>'DD Month YYYY'],
            ['format' => 'l F j Y','format_view'=>'Day Month DD YYYY']
        ];
        return $time_frmt;
    }


    public static function system_control($columns = null , $company_id = null)
    {
        if(!$company_id && Auth::user()?->company_id){
            $company_id = Auth::user()?->company_id;
        }
        else if(!$company_id){
            $company_id = Controller::model('Company','DynamicMaster')::where('default_company','Yes')->pluck('id')->first();
            if(!$company_id){
                $company_id = 1;
            }
        }
        if(gettype($columns) == "array"){
            $data = Controller::model('SystemControl','SystemControl')::where('company_id',$company_id)->select($columns)->first();
        }
        else if(gettype($columns) == "string"){
            $data = Controller::model('SystemControl','SystemControl')::where('company_id',$company_id)->pluck($columns)->first();
        }
        else{
            $data = Controller::model('SystemControl','SystemControl')::where('company_id',$company_id)->first();
        }
        return $data;
    }



}
