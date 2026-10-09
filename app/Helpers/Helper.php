<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

if (!function_exists('module_path')) {
    function module_path($path = '')
    {
        return base_path('Modules/' . $path);
    }
}

if (!function_exists('encrypt')) {
    function encrypt($data)
    {
        if($data){
            return Crypt::encryptString($data);
        }
        return '';
    }
}

if (!function_exists('decrypt')) {
    function decrypt($data)
    {
        return Crypt::decryptString($data);
    }
}

if (!function_exists('addToLog')) {
    function addToLog($source,$source_id,$message,$type=null)
    {
       $model = Controller::model('ActivityLog','DynamicMaster');
       $log = new $model;
       $log->source = $source;
       $log->source_id = $source_id;

       if($type==null)
       {
        $log->message = Auth::user()->name.' '.$message;
        $log->created_by = Auth::user()->id;
       }
       else{
        $log->message = 'System'.$message;
        $log->created_by = null;
       }
       $log->save();
       return true;
    }
}

if (!function_exists('get_model_instance')) {
    function get_model_instance($model,$module=null)
    {
        if($module)
        {
            $fqcn = "Modules\\$module\\Models\\$model";

        }
        else{
            $fqcn ="App\\Models\\$model";
        }

        if (!class_exists($fqcn)) {
            throw new \Exception("Model $fqcn not found.");
        }
        return new $fqcn;
    }
}


function status_check($status)
{
    if ($status == "") {
        return '<span class="status-badge-completed">' . $status . '</span>';
    }
    elseif ($status == "Draft") {
        return '<span class="status-badge-pending">' . $status . '</span>';


    }
    elseif ($status == "Published" || $status == "In review") {
        return '<span class="status-badge-pending">' . $status . '</span>';
    }
    elseif ($status == "Inactive") {
        return '<span class="status-badge-pending">' . $status . '</span>';
    }
     elseif ($status == "Yes") {
        return '<span class="status-badge-pending">' . $status . '</span>';
    }
    elseif ($status == "") {
        return '<span class="status-badge-active">' . $status . '</span>';
    }
        elseif ($status == "Active" || $status == "Approved" || $status =="Awarded") {
        return '<span class="status-badge-success">' . $status . '</span>';
    }
    else {
        return '<span class="status-badge-pending">' . $status . '</span>';
    }

}

function date_formate($date)
{
    if($date)
        return date('j F, Y', strtotime($date));
    else
        return '';
}

function date_time_formate($date)  // change date and time formate to show on page by Ashish
{
    if($date)
        return Carbon::parse($date)->format('j F Y , h:i A');
    else
        return '';
}

function catch_log($e,$type = null)
{
    \Log::error('Error in '.$type.' : ' . $e->getMessage(), [
        'line' => $e->getLine(),
        'file' => $e->getFile(),
        'trace' => $e->getTraceAsString(),
    ]);
}


function breadcrums($label,$label_array=[],$route_array=[])
{
    $html = '<div class="flex justify-between pb-3"> <div> <h1 class="text-lg font-bold text-gray-700">'.label_name($label).'</h1> </div>';

        $cnt = count($route_array);
        if($cnt>0){
            $html.= '<nav class="flex max-sm:hidden" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">';
        }
        switch($cnt){
            case 1 : $html.= '<li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[0]).'>'.label_name($label_array[0]).'</a><i class="ri-speed-mini-line text-gray-400 p-1"></i></li>'; break;
            case 2 : $html.= '<li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[0]).'>'.label_name($label_array[0]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li><li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[1]).'>'.label_name($label_array[1]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li>'; break;
            case 3 : $html.= '<li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[0]).'>'.label_name($label_array[0]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li><li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[1]).'>'.label_name($label_array[1]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li><li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[2]).'>'.label_name($label_array[2]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li>'; break;
            case 4 : $html.= '<li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[0]).'>'.label_name($label_array[0]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li><li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[1]).'>'.label_name($label_array[1]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li><li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[2]).'>'.label_name($label_array[2]).'</a>  <i class="ri-speed-mini-line text-gray-400 p-1"></i>  </li><li class="inline-flex items-center">'.only_for_breadcrum_route($route_array[3]).'>'.label_name($label_array[3]).'</a> <i class="ri-speed-mini-line text-gray-400 p-1"></i> </li>'; break;
            default : $html.= '';
        }

        $html.= '<li class="inline-flex items-center text-xs cursor-not-allowed"><a = "#">'.label_name($label_array[$cnt]).'</a></li>';
        if($cnt>0){
            $html.= '</ol> </nav>';
        }
        $html.= '</div>';


    return $html;
}

function button_route($routes)
{
    return '<a href='.($routes).'>';
}

function only_for_breadcrum_route($routes)
{
    if(gettype($routes) != "array"){
        return '<a class="inline-flex items-center text-sm text-primary font-bold hover:text-blue-600" href='.route($routes);
    }
    return '<a class="inline-flex items-center text-sm text-primary font-bold hover:text-blue-600" href='.route($routes[0],["id"=>$routes[1]]);
}

function button_route_withid($routes,$id)
{
    if($routes=='' || $routes==null){
        return 'href ="#" data-bs-toggle="offcanvas" ';
    }
    else if($id =='' || $id==null){
        return 'href='.route($routes).'';
    }
    else{
        return 'href='.route($routes,["id"=>base64_encode(convert_uuencode($id))]);
    }
}

function home_url($routes)
{
    return '<a href='.route($routes).'>';
}

function label_name($lbl)
{
    return __($lbl);
}
function no_format($no){
    if($no){
        return number_format((float)$no, 3, '.', '');
    }
    return '0.000';
}

function back_button($indexRoute = 'back', $key_array = [], $val_array = [])
{
    $previous = url()->previous();
    $current  = url()->current();

    $prevClean = strtok($previous, '?');
    $currClean = strtok($current, '?');

    $invalid =
        empty($prevClean) ||
        $prevClean === $currClean ||
        preg_match('/\/(edit|view|show|create)\b/i', $prevClean) ||
        preg_match('/\/\d+$/', $prevClean);

    if ($indexRoute === 'back') {

        // agar refresh ke baad invalid hai → HTTP_REFERER bhi kaam ka nahi
        if ($invalid) {
            // 🧠 smart fallback → module index guess
            // e.g. /plan-mapping/12/edit → /plan-mapping
            $segments = request()->segments();
            $url = url($segments[0] ?? '/dashboard');
        } else {
            $url = $prevClean;
        }
    }

    else {
        if ($invalid) {
            switch (count($key_array)) {
                case 1:
                    $url = route($indexRoute, [$key_array[0] => $val_array[0]]);
                    break;
                case 2:
                    $url = route($indexRoute, [
                        $key_array[0] => $val_array[0],
                        $key_array[1] => $val_array[1],
                    ]);
                    break;
                default:
                    $url = route($indexRoute);
            }
        } else {
            $url = $prevClean;
        }
    }

    return button_route($url) . '
        <button type="button"
            class="flex gap-1 items-center px-4 py-2
                   text-sm font-medium text-gray-900 hover:bg-gray-100">
            <i class="ri-arrow-left-line"></i> ' . label_name('common.back') . '
        </button>
    </a>';
}



function overall_logo()
{
    return asset('assets/image/logo/logo-black.png');
}

function addNotification($user_id,$message,$url=null)
{
    $data = CustomNotifications::create([
        'user_id'=>$user_id,
        'message'=>$message,
        'url' => $url,
    ]);
    return true;
}

function current_date()
{
    return now();
}

function filter_button(){
$html = '<button type="button"
        class=" fil-btn  inline-flex hover-scale gap-2 border-l-[1px] items-center px-2 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class="ri-filter-line text-blue-500 font-semibold"></i>
        <span id="toggle-label" class="whitespace-nowrap"> '.label_name('common.Apply Filter').'</span>

    </button>';
    return $html;
}

function get_employee($cmp_id = null)
{
    $data = Controller::model('Employee','DynamicMaster')::select('name','id','code','company_id')->where('status','Active')->get();
    if($cmp_id)
    {
        $data = $data->where('company_id',$cmp_id);
    }
    return $data;


}
function get_employee_by_id($id)
{
    $data = Controller::model('Employee','DynamicMaster')::where('id',$id)->first();

    return $data;


}

function masterParentData($typeName, $companyId = null)
{
    $model = Controller::model('MasterParent', 'DynamicMaster');

    return $model::with('masterChild')->whereHas('masterType', function ($query) use ($typeName) {
            $query->where('name', $typeName);
        })
        ->when($companyId, function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->where('status','Active')
        ->get();

}

// master_type_name    column   col_value   comp_id
function masterParentSingleData($typeName, $column, $col_value, $companyId = null)
{
    $model = Controller::model('MasterParent', 'DynamicMaster');

    return $model::where($column, $col_value)
        ->whereHas('masterType', function ($query) use ($typeName) {
            $query->where('name', $typeName);
        })
        ->when($companyId, function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->where('status','Active')
        ->first();
}


function masterChildData($parent_id)
{
    $model = Controller::model('MasterChild', 'DynamicMaster');
    return $model::where('master_parent_id',$parent_id)->where('status','Active')->get();
}

function get_users($cmp_id = null, $user_type = null)
{
    $query = Controller::model('User')::select('name', 'id', 'email')->where('status', 'Active');

    if ($cmp_id) {
        $query = $query->forCompany($cmp_id);
    }

    if ($user_type) {
        $query = $query->where('type', $user_type);
    }

    return $query->get();
}


function user_company_permission()
{
    $data = Controller::loginandcompany();
    $company = Controller::model('UserCompany','DynamicMaster')::with('company')->where('status', 'Active')->where('user_id', $data[0])->get();
    return [$company, $data[1]];
}



