<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class BankRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name'=>'required',
            'account_no'=>'required',
            'bank_group_id'
        ];
    }
}

