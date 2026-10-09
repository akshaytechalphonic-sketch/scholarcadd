<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseJsonRequest;

class DocumentRequest extends BaseJsonRequest
{
    public function rules(): array
    {
        return [
            'attachment_fileee.*' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xlsx,xls,csv',
            'source_id' => 'required',
            'source' => 'required',
        ];
    }
}

