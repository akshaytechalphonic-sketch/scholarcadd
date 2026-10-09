<?php

namespace Modules\Course\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class CourseRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
        ];
    }
}

