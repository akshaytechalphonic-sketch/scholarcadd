<?php

namespace  Modules\DynamicMaster\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;


class CompanyRepository
{
    public function create()
    {
        $country_data = Controller::model('Country','DynamicMaster')::ActiveOnly()->get();

        return $country_data;
    }

    public function store($request)
    {
        Controller::db()::beginTransaction();
        try {
            $cmp_and_user = Controller::loginandcompany();
            $user_id = $cmp_and_user[0];
            $model = Controller::model('Company','DynamicMaster');

            if ($request->company_id) {
                $id = decrypt($request->company_id);
                $company =  $model::find($id);

                if (!$company) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => 'Company not found with the given ID.',
                    ];
                }
                $company->updated_by = $user_id;
            } else {
                $company = new $model();
                $company->code = $request->code;
                $company->name = $request->name;
                $company->registration_no = $request->registration_no;
                $company->vat_no = $request->vat_no;
                $company->created_by = $user_id;
            }

            $company->about = $request->about;
            $company->address = $request->address;
            $company->city = $request->city;
            $company->state = $request->state;
            $company->zip_code = $request->zip_code;
            $company->country_id = $request->country_id;
            $company->website = $request->website;
            $company->status = $request->status;
            $company->origin_date = $request->origin_date;
            $company->contact_number = $request->contact_number;
            $company->payment_alias_name = $request->payment_alias_name;

            $company->save();

            if ($request->hasFile('pic')) {
                $image = Controller::helper('CommonHelper')::folder_name($company->id, 'Company', 'Company Logo', $request->file('pic'), 5, 'images','DynamicMaster');
                if (empty($image)) {
                    Controller::db()::rollback();
                    return [
                        'status' => false,
                        'message' => __('common.folder-name-not-generating'),
                    ];
                }
                $company->company_logo = $image[1];
                $company->save();
            }
            Controller::db()::commit();

            return [
                'status' => true,
                'message' => 'Company saved successfully.',
                'data' => $company,
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
         $data = Controller::model('Company','DynamicMaster')::with('creator')
        ->orderby('id','desc');
        return $data;
    }

    public function edit($request)
    {
        $id = decrypt($request->id);
        $data = Controller::model('Company','DynamicMaster')::where('id',$id)->first();
        return $data;
    }







}

