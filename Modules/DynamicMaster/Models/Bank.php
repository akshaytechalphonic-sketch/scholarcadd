<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_bank';

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }

    public function bankGroup()
    {
        $model = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($model, 'bank_group_id');
    }

    public function country()
    {
        $userModel = get_class(get_model_instance('Country','DynamicMaster'));
        return $this->belongsTo($userModel, 'country_id');
    }

    public function mainAccount()
    {
        $userModel = get_class(get_model_instance('GLAccount','Financial'));
        return $this->belongsTo($userModel, 'gl_account_id');
    }

    public function currency()
    {
        $userModel = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($userModel, 'currency_id');
    }

    // public function city()
    // {
    //     $userModel = get_class(get_model_instance('City','DynamicMaster'));
    //     return $this->belongsTo($userModel, 'country_id');
    // }
}
