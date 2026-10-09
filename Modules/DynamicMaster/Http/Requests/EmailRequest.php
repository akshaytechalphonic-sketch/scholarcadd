<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class EmailRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'veriable' => 'required',
            'email_master_id'=> 'required',
            'name'=> 'required',
            'subject'=> 'required',
            'content'=> 'required',
        ];
    }
}
    
