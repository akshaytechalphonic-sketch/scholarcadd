<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Inject HTML validation errors into the validator
     */
    // protected function getValidatorInstance()
    // {
    //     $validator = parent::getValidatorInstance();

    //     $input = $this->all();
    //     array_walk_recursive($input, function ($value, $key) use ($validator) {
    //         if (is_string($value) && $value !== strip_tags($value)) {
    //             $validator->after(function ($validator) use ($key) {
    //                 $validator->errors()->add($key, "The $key field must not contain HTML tags.");
    //             });
    //         }
    //     });

    //     return $validator;
    // }

    /**
     * Handle validation failure
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            validationError($validator->errors(), true)
        );
    }
}
