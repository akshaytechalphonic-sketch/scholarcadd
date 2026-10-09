<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class EmployeeRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'contact_no'=> 'required',
            'status'=> 'required',
            'email'=>'required',
            'name'=>'required',
            'empCode'=>'required',
            'department_id'=>'required',
            'cost_center'=>'required',
            'business_unit'=>'required',
            'country_id'=>'required'
        ];
    }
}
