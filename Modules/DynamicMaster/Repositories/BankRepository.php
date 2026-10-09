<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;


class BankRepository
{
    public function create($id=null)
    {
        $data=null;
        $companyId = Controller::loginandcompany();
        if($id)
        {
            $id = decrypt($id);
            $data = Controller::model('Bank','DynamicMaster')::where('id',$id)->first();
        }
        $countries = Controller::model('Country','DynamicMaster')::ActiveOnly()->get();
        $gl_accounts = Controller::model('GLAccount','Financial')::where('is_active',1)->get();
        $cities =[];
        $bank_groups =  masterParentData('BankGroup', $companyId[1]);
        $currencies = masterParentData('Currency', $companyId[1]);
        return view('dynamicmaster::bank.add',compact('countries','gl_accounts','cities','data','bank_groups','currencies'));
    }

    public function store($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $user_id = $cmp_and_user[0];
            $model = Controller::model('Bank','DynamicMaster');

            if ($request->bank_id) {
                $id = decrypt($request->bank_id);
                $bank =  $model::find($id);

                if (!$bank) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => 'Bank not found with the given ID.',
                    ];
                }
                $bank->updated_by = $user_id;
            } else {
                $bank = new $model();
                $bank->account_no = $request->account_no;
                $bank->bank_group_id = $request->bank_group_id;
                $bank->name = $request->name;
                $bank->created_by = $user_id;
            }

            $bank->country_id = $request->country_id;
            $bank->city_id = $request->city_id;
            $bank->gl_account_id = $request->gl_account_id;
            $bank->currency_id = $request->currency_id;
            $bank->email = $request->email;
            $bank->phone_no = $request->phone_no;
            $bank->contact_name = $request->contact_name;
            $bank->description = $request->description;
            $bank->iban = $request->iban;
            $bank->enable_reconciliation = $request->enable_reconciliation;
            $bank->company_id = $cmp_and_user[1];
            $bank->save();
            Controller::db()::commit();
            return [
                'status' => true,
                'message' => 'bank saved successfully.',
                'data' => $bank,
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
         $data = Controller::model('Bank','DynamicMaster')::with('creator')
        ->orderby('id','desc');
        return $data;
    }

}

