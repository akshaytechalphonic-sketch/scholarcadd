<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class EmailRepository
{ 
    public function create()
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = masterParentData('EmailMaster',$cmp_and_user[1]);
        return $data;
    }

    public function store($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $user_id = $cmp_and_user[0];
            $model = Controller::model('EmailTemplate','DynamicMaster');


            if($request->data_id){
                $id = decrypt($request->data_id);
                $data = $model::find($id);
            }
            else{ 
                $data = new $model();
 

                $data->code = Controller::helper('NumberSequenceHelpers')::generateNumberSequence('EmailTemplate','code','EmailTemp-',6,'DynamicMaster');
                $data->created_by = $cmp_and_user[0];
            }
            
            $data->name = $request->name;
            $data->email_master_id = $request->email_master_id;
            $data->veriable = $request->veriable;
            $data->subject = $request->subject;
            $data->content = $request->content;
            $data->updated_by = $cmp_and_user[0];
            $data->company_id = $cmp_and_user[1];
            $data->status = "Active";
            $data->save();

 
            Controller::db()::commit();

            return [
                'status' => true,
                'message' => 'Email Template saved successfully.',
                'data' => $data,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function yajraList()
    {
        $data = Controller::model('EmailTemplate','DynamicMaster')::with('creator')->orderby('id','desc');
        return $data;
    }

    public function edit($request)
    { 
        $id = decrypt($request->id);
        $data = Controller::model('EmailTemplate','DynamicMaster')::where('id',$id)->first();
        return $data;
    }
    public function get_all_email_template(Request $request)
    {          
        $html = [];
        try{
            $cmp_and_user = Controller::loginandcompany();
            $data = Controller::model('EmailTemplate','DynamicMaster')::ActiveOnly($cmp_and_user[0])->get();            
            foreach($data as $dt){
                if($request->email_temp_id == $dt?->id)
                    $html[] = '<option value="'.$dt?->id.'" selected> '.$dt?->name.' / '.$dt?->email_master?->name.' </option>';
                else
                    $html[] = '<option value="'.$dt?->id.'"> '.$dt?->name.' / '.$dt?->email_master?->name.' </option>';
            }
            return $html;
        } catch (\Exception $e) {
            return $html;
        }
    }
    

}

