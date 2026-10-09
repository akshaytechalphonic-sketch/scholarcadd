<?php

namespace Modules\Loginer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistartionRequest extends Model
{
    use HasFactory;

   protected $table ="tbl_registration_request";
   protected $guarded = [''];

    public function user()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'user_id');
    }

}
