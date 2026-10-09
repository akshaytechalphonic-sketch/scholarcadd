<?php

namespace Modules\DynamicMaster\Http\Requests;

use App\Http\Requests\BaseJsonRequest;

class CommentRequest extends BaseJsonRequest
{
    public function rules(): array
    {
        return [
            'attachment_file.*' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx,xlsx,xls',
            'source_id' => 'required',
            'source' => 'required',
            'comment'=>'required'
        ];
    }
}

