<?php

namespace Modules\Course\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class LocationRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'location_name' => 'required',
        ];
    }
}

