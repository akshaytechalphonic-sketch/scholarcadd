<?php

namespace Modules\DynamicMaster\Http\Requests;
 
use App\Http\Requests\BaseFormRequest;

class CompanyRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required',
            'registration_no' => 'required',
            'name' => 'required',
            'vat_no'=> 'required',
            'origin_date'=> 'required',
            'contact_number'=> 'required',
            'city'=> 'required',
            'state'=> 'required',
            'zip_code'=> 'required',
            'country_id'=> 'required',
            'status'=> 'required',
            'address'=> 'required'
        ];
    }
}
