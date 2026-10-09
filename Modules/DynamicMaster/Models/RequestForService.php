<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class RequestForService extends Model
{
    protected $table = 'tbl_request_for_services';
    protected $guarded = [];




    protected $casts = [
        'full_name' => 'encrypted',
        'email' => 'encrypted',
        'phone' => 'encrypted',
        'message' => 'encrypted',
        'agreed' => 'boolean',
    ];

}
