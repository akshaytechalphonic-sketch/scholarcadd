<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;


class TaxRepository
{

    public function store($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $user_id = $cmp_and_user[0];
            $model = Controller::model('TaxType','DynamicMaster');
            if ($request->id) {
                $id = decrypt($request->id);
                $taxType =  $model::find($id);
                if (!$taxType) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => 'Tax Type not found with the given ID.',
                    ];
                }
            } else {
                $taxType = new $model();
                $taxType->tax_type_name = $request->tax_type_name;
                $taxType->tax_type_code = $request->tax_type_code;
            }
            $taxType->is_active = $request->is_active;
            $taxType->description = $request->description;
            $taxType->save();
            Controller::db()::commit();
            return [
                'status' => true,
                'message' => 'Tax Type saved successfully.',
                'data' => $taxType,
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
         $data = Controller::model('TaxType','DynamicMaster')::with('creator')
        ->orderby('id','desc');
        return $data;
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = Controller::model('TaxType','DynamicMaster')::where('id',$id)->first();
        return $data;
    }

    // Tax Functions

    public function tax_store($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $user_id = $cmp_and_user[0];
            $model = Controller::model('Tax','DynamicMaster');
            if ($request->id) {
                $id = decrypt($request->id);
                $tax =  $model::find($id);
                if (!$tax) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => 'Tax not found with the given ID.',
                    ];
                }
                //$tax->updated_by = $user_id;
            } else {
                $tax = new $model();
                $tax->tax_type_id = $request->tax_type_id;
                $tax->tax_code = $request->tax_code;

                // $tax->created_by = $user_id;
            }
            $tax->tax_rate = $request->tax_rate;
            $tax->is_active = $request->is_active;
            $tax->tax_rate_description = $request->tax_rate_description;
            $tax->effective_from = $request->effective_from;
            $tax->effective_until = $request->effective_until;
            $tax->save();
            Controller::db()::commit();
            return [
                'status' => true,
                'message' => 'Tax saved successfully.',
                'data' => $tax,
            ];
        } catch (\Exception $e) {
            Controller::db()::rollback();
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function tax_yajraList()
    {
         $data = Controller::model('Tax','DynamicMaster')::with('creator')
        ->orderby('id','desc');
        return $data;
    }

    public function tax_edit($request)
    {
        $id = decrypt($request->id);
        $data = Controller::model('Tax','DynamicMaster')::where('id',$id)->first();
        return $data;
    }

    public function tax_create()
    {
        $data = Controller::model('TaxType','DynamicMaster')::where('is_active', 1)->get();
        return $data;
    }







}

