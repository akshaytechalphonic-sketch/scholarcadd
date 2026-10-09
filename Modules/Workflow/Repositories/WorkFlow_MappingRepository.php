<?php

namespace  Modules\Workflow\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WorkFlow_MappingRepository
{ 
    public function yajraList()
    {
        // $companyId = Controller::loginandcompany();
        // return Controller::model('WorkflowMap','Workflow')::where('company_id', $companyId[1])->orderby('id','DESC');
        $companyId = Controller::loginandcompany();
        $map_time_data = [];
        $workflow = Controller::model('WorkflowSetup','Workflow')::ActiveOnly($companyId[1])->whereNull('workflow_map_id')->get();

        $data = Controller::model('WorkflowMap','Workflow')::get();

        foreach($data as $map_tb){
            $map_time_data[] = Controller::model('WorkflowSetup','Workflow')::with('workflow_map')->where('workflow_map_id',$map_tb?->id)->first();
        }
        $final_data = ['data'=>$data,'workflow'=>$workflow,'map_time_data'=>$map_time_data];
        return $final_data;
    }

    // public function store($request)
    // {
    //     try {
    //         $companyId = Controller::loginandcompany();
    //         $i= 0;
    //         if($request->sequence_code && count($request->sequence_code)>0){
    //             foreach ($request->sequence_code as $number) {
    //                 $old = Controller::model('Numbersequence','NumberSequence')::ActiveOnly($companyId[1])->where('code',$number)->first();
    //                 $j = 0;
    //                 if($old){ $j++; }
    //                 else {
    //                     $rng = $request->range_no[$i];
    //                     $formate=$request->constant_no[$i];
    //                     while($rng){
    //                         $formate=$formate.'#';
    //                         $rng--;
    //                     }
    //                     Controller::model('Numbersequence','NumberSequence')::create([
    //                         'code'=> $number,
    //                         'name' => $request->sequence_name[$i],
    //                         'constant_no' => $request->constant_no[$i],
    //                         'range_no' => $request->range_no[$i],
    //                         'format' => $formate,
    //                         'created_by'=>$companyId[0],
    //                         'company_id'=>$companyId[1],
    //                     ]);
    //                 }
    //                 $i++;
    //             }
    //             if($j>0){
    //                 return [
    //                     'status' => false,
    //                     'message' => 'This Record is already exist.',
    //                 ];
    //             }
    //              return [
    //                     'status' => true,
    //                     'message' => 'Record is created successfully'
    //                 ];

    //         }
    //         else{
    //              return [
    //                     'status' => false,
    //                     'message' => 'Please add atleast one sequence !',
    //                 ];
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }

    // public function index_map($request)
    // {
    //     $companyId = Controller::loginandcompany();
    //     $map_time_data = [];
    //     $no_seqs = Controller::model('Numbersequence','NumberSequence')::ActiveOnly($companyId[1])->whereNull('numbersequence_map_id')->get();

    //     $data = Controller::model('NumberSequenceMap','NumberSequence')::with('creator')->get();

    //     foreach($data as $map_tb){
    //         $map_time_data[] = Controller::model('Numbersequence','NumberSequence')::with('numbersequence_map')->where('numbersequence_map_id',$map_tb?->id)->first();

    //     }
    //     $final_data = ['data'=>$data,'no_seqs'=>$no_seqs,'map_time_data'=>$map_time_data];
    //     return $final_data;

    // }


    public function mapWorkflow($request)
    { 
        try {
            $map_id = decrypt($request->formid);            
            $setup_id = $request->map_workflow;

            $companyId = Controller::loginandcompany();   
            $old_data = Controller::model('WorkflowSetup','Workflow')::ActiveOnly($companyId[1])->where('workflow_map_id',$map_id)->get();
            foreach($old_data as $old){
                $old->workflow_map_id = null;
                $old->save();
            } 
            if($map_id && $setup_id){
                $cmp_and_user = Controller::loginandcompany();
                $todayDate = date("Y-m-d");
                $setup_data = Controller::model('WorkflowSetup','Workflow')::ActiveOnly($companyId[1])->find($setup_id);            
                $setup_data->workflow_map_id = $map_id;
                $setup_data->mapping_date = $todayDate;
                if($setup_data->version){
                    $setup_data->version=$setup_data->version+1;
                }
                else{
                    $setup_data->version = 1;
                }        
                $setup_data->updated_by = $cmp_and_user[0];            
                $setup_data->save();
                
                $mod = Controller::model('Workflow_MapLog','Workflow');        
                $new_record = new $mod();
                $new_record->company_id = $cmp_and_user[1];
                $new_record->map_id=$map_id;
                $new_record->workflow_setup_id = $setup_id;
                $new_record->mapping_date = $todayDate;
                $new_record->created_by = $cmp_and_user[0];
                $new_record->version=$setup_data->version;
                $new_record->save();
                return ['status'=>true,'message'=>'Mapped successfully !'];
            // return $new_record->id;
        }
        else{
            return ['status' => false, 'message' => "Invalid payload"]; 
        }

        } catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage(), ];
        }
    }
    // public function destroy(Request $request)
    // {
    //     $id = decrypt($request->id);
    //     $data = Controller::model('Numbersequence','NumberSequence')::find($id);
    //     if($data?->numbersequence_map_id){
    //         return ['status'=>false,'message'=>'Number Sequence is mapped !'];
    //     }
    //     else{
    //         $line = Controller::model('Numbersequence','NumberSequence')::find($id);
    //         $line->delete();
    //         return ['status'=>true,'message'=>'Record is deleted successfully !'];
    //     }
    // }

}

