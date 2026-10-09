<?php

namespace Modules\Loginer\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class UserAddRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'name'=>['required'],
        ];
    }
}
