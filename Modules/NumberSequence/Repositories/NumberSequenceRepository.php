<?php

namespace  Modules\NumberSequence\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class NumberSequenceRepository
{
    public function yajraList()
    {
         $companyId = Controller::loginandcompany();
         return Controller::model('NumberSequence','NumberSequence')::where('company_id', $companyId[1])->orderby('id','DESC');
    }

    public function store($request)
    {
        try {
            $companyId = Controller::loginandcompany();
            $i= 0;
            if($request->sequence_code && count($request->sequence_code)>0){
                foreach ($request->sequence_code as $number) {
                    $old = Controller::model('NumberSequence','NumberSequence')::ActiveOnly($companyId[1])->where('code',$number)->first();
                    $j = 0;
                    if($old){ $j++; }
                    else {
                        $rng = $request->range_no[$i];
                        $formate=$request->constant_no[$i];
                        while($rng){
                            $formate=$formate.'#';
                            $rng--;
                        }
                        Controller::model('NumberSequence','NumberSequence')::create([
                            'code'=> $number,
                            'name' => $request->sequence_name[$i],
                            'constant_no' => $request->constant_no[$i],
                            'range_no' => $request->range_no[$i],
                            'format' => $formate,
                            'created_by'=>$companyId[0],
                            'company_id'=>$companyId[1],
                        ]);
                    }
                    $i++;
                }
                if($j>0){
                    return [
                        'status' => false,
                        'message' => 'This Record is already exist.',
                    ];
                }
                 return [
                        'status' => true,
                        'message' => 'Record is created successfully'
                    ];

            }
            else{
                 return [
                        'status' => false,
                        'message' => 'Please add atleast one sequence !',
                    ];
            }
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function index_map($request)
    {
        $companyId = Controller::loginandcompany();
        $map_time_data = [];
        $no_seqs = Controller::model('NumberSequence','NumberSequence')::ActiveOnly($companyId[1])->whereNull('numbersequence_map_id')->get();

        $data = Controller::model('NumberSequenceMap','NumberSequence')::with('creator')->get();

        foreach($data as $map_tb){
            $map_time_data[] = Controller::model('NumberSequence','NumberSequence')::with('numbersequence_map')->where('numbersequence_map_id',$map_tb?->id)->first();

        }
        $final_data = ['data'=>$data,'no_seqs'=>$no_seqs,'map_time_data'=>$map_time_data];
        return $final_data;

    }


    public function store_mapping($request)
    {
        try {
            $id = decrypt($request->formid);
            $number_sequence_id = trim($request->sequencemaping);
            $companyId = Controller::loginandcompany();
            $old_data = Controller::model('NumberSequence','NumberSequence')::ActiveOnly($companyId[1])->where('numbersequence_map_id',$id)->get();
            foreach($old_data as $old){
                $old->numbersequence_map_id = null;
                $old->save();
            }
            $companyId = Controller::loginandcompany();
            $data = Controller::model('NumberSequence','NumberSequence')::find($number_sequence_id);
            $data->numbersequence_map_id = $id;
            $data->maping_date= now();
            $data->updated_by= $companyId[0];
            $data->save();

            return ['status'=>true,'message'=>'Mapped successfully !'];

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    public function destroy(Request $request)
    {
        $id = decrypt($request->id);
        $data = Controller::model('NumberSequence','NumberSequence')::find($id);
        if($data?->numbersequence_map_id){
            return ['status'=>false,'message'=>'Number Sequence is mapped !'];
        }
        else{
            $line = Controller::model('NumberSequence','NumberSequence')::find($id);
            $line->delete();
            return ['status'=>true,'message'=>'Record is deleted successfully !'];
        }
    }

}

