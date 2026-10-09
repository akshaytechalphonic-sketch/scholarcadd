<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $table ="tbl_master_email_template";
    protected $guarded = [];

     public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }

    public function updator()
    {
         $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'updated_by');
    }
    public function company()
    {
         $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
    public function email_master()
    {
         $model = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($model, 'email_master_id');
    }
    public function scopeActiveOnly($query,$cmp)
    {
        return $query->where('status', 'Active')->orderBy('id', 'DESC')->where('company_id',$cmp);
    } 


}
