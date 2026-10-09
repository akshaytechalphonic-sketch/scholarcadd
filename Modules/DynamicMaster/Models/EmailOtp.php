<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $table = 'tbl_email_otps';

    protected $fillable = ['email', 'otp', 'expires_at'];

    protected $dates = ['expires_at'];
}
