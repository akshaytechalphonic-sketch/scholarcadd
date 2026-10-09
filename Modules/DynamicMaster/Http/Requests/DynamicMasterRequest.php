<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseJsonRequest;

class DynamicMasterRequest extends BaseJsonRequest
{
    public function rules(): array
    {
        return [
            'masters_name' => 'required',
            'masters_description' => ['required']
        ];
    }
}
