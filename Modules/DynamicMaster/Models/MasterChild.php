<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterChild extends Model
{
    use HasFactory;

    protected $table ="tbl_master_child";
    protected $guarded = [];


    public function scopeValidEntity($query,$cmp_id = null)
    {
        if($cmp_id){
            return $query->orderBy('id', 'DESC')->where('company_id',$cmp_id);
        }
        return $query->orderBy('id', 'DESC');
    }
    
    public function masterParent()
    {
         $model = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($model,'master_parent_id');
    }
    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }


}
