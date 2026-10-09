<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class DynamicMasterRepository
{

    public function masteryajraList($type)
    {
        $cmp_id = null;
        $check_for_company = ['FeePayment','Department','FinancialDimension','EvaluationParameter','RatingCriteria','Ranking','Position','Employee','Item','Project','Site','Warehouse','EvaluationGroup','EvaluationMembers','FeePayment','Category','SubCategory'];
        if(in_array($type,$check_for_company)){
            $cmp_id = Controller::user()->company_id;
        }
        $type_id = Controller::model('MasterType','DynamicMaster')::where('name',$type)->pluck('id')->first();
        $data = Controller::model('MasterParent','DynamicMaster')::ValidEntity($cmp_id)
        ->with('creator')
        ->where('master_type_id',$type_id)
        ->orderby('id','desc');
        return $data;
    }

    public function masteryajraList_child($type, $request)
    {
         $p_id = null;
        if($request->parent_id)
        {
            $p_id = decrypt($request->parent_id);

        }
        
        $cmp_id = null;
        $check_for_company = ['FeePayment','Department','FinancialDimension','EvaluationParameter','RatingCriteria','Ranking','Position','Employee','Item','Project','Site','Warehouse','EvaluationGroup','EvaluationMembers','FeePayment','Category','SubCategory'];
        if(in_array($type,$check_for_company)){
            $cmp_id = Controller::user()->company_id;
        }
        $type_id = Controller::model('MasterType','DynamicMaster')::where('name',$type)->pluck('id')->first();
        $parent_ids = Controller::model('MasterParent','DynamicMaster')::where('master_type_id',$type_id)->pluck('id')->toArray();
        $data = Controller::model('MasterChild','DynamicMaster')::ValidEntity($cmp_id)
        ->when($request->parent_id && $p_id, function($q) use($p_id){
            $q->where('master_parent_id',$p_id);
        })
        ->with(['creator','masterParent'])
        ->whereIn('master_parent_id',$parent_ids)
        ->orderby('id','desc');
        return $data;
    }

    public function get_parent_list($type)
    { 
        $type_id = Controller::model('MasterType','DynamicMaster')::where('name',$type)->pluck('id')->first();
        $parent_data = Controller::model('MasterParent','DynamicMaster')::ActiveOnly()->where('master_type_id',$type_id)->get();
         
        return $parent_data;
    }

    public function edit($request)
    {
        if($request->master_parent_id)
        {
            $id= decrypt($request->master_parent_id);
            $data = Controller::model('MasterParent','DynamicMaster')::where('id',$id)->first();

        }
        elseif($request->master_child_id)
        {
            $id= decrypt($request->master_child_id);
            $data = Controller::model('MasterChild','DynamicMaster')::where('id',$id)->with('masterParent')->first();
        }
        else{
            $data = [];
        }

        return $data;
    }

    public function update($request)
    {
        $cmp_and_user = Controller::loginandcompany();
        if($request->master_parent_id)
        {
            $id= decrypt($request->master_parent_id);
            $data = Controller::model('MasterParent','DynamicMaster')::where('id',$id)->update([
                "description"=>$request->masters_description,
                "status"=>$request->masters_status,
                'updated_by'=>$cmp_and_user[0],
            ]);

        }
        elseif($request->master_child_id)
        {
            $id= decrypt($request->master_child_id);
            $data = Controller::model('MasterChild','DynamicMaster')::where('id',$id)->update([
                "description"=>$request->masters_description,
                "status"=>$request->masters_status,
                'updated_by'=>$cmp_and_user[0],
            ]);
        }
        else{
            $data = [];
        }

    }

    public function store($request,$next_code,$master_type_id=null)
    { 
        $cmp_and_user = Controller::loginandcompany();
        if($master_type_id)
        {
            $model = Controller::model('MasterParent','DynamicMaster');
            $data= new $model();
            $data->master_type_id = $master_type_id;
            $data->employee_id = $request->employee_id;

        }
        elseif($request->master_parent_id)
        {
            $model = Controller::model('MasterChild','DynamicMaster');
            $data= new $model();
             $data->master_parent_id = $request->master_parent_id;             
        }
        else{
            return false;
        }

        $data->code = $next_code;
        $data->name = $request->masters_name;
        $data->description = $request->masters_description;
        $data->status = "Active";
        $data->created_by = $cmp_and_user[0];
        $data->company_id = $cmp_and_user[1];
        $data->save();
        return $data?->id;
    }
    public function switch_company($company_id)
    {  
        $user_id = Controller::user_id();
        $cmp_id = decrypt($company_id); 
        $user = Controller::model('User')::find($user_id);
        $user->company_id = $cmp_id;
        $user->save();
        return response()->json([ 'status' => true], 200);
    }

    

}

