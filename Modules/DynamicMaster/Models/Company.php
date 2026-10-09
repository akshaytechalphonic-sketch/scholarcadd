<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table ="tbl_company";
    protected $guarded = [];
    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'Active');
    }

    public function company()
    {
        $model = get_class(get_model_instance('Company'));
        return $this->belongsTo($model, 'company_id');
    }

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }



}
