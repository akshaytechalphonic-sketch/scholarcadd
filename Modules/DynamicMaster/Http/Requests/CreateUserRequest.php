<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class CreateUserRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
           'role_ids' => 'required|array|min:1',
            'employee_id'=> 'required',
        ];
    }
}
