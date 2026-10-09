<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Http\Controllers\Controller;

class MasterParent extends Model
{
    use HasFactory;

    protected $table ="tbl_master_parent";
    protected $guarded = [];

    public function scopeValidEntity($query,$cmp_id = null)
    {
        if($cmp_id){
            return $query->orderBy('id', 'DESC')->where('company_id',$cmp_id);
        }
        return $query->orderBy('id', 'DESC');
    }

    public function scopeActiveOnly($query,$cmp_id = null)
    {
        if($cmp_id){
            return $query->where('status', 'Active')->orderBy('id', 'DESC')->where('company_id',$cmp_id);
        }
        return $query->where('status', 'Active')->orderBy('id', 'DESC');
    }
    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }

    public function masterType()
    {
         $model = get_class(get_model_instance('MasterType','DynamicMaster'));
        return $this->belongsTo($model,'master_type_id');
    }

    public function masterChild()
    {
         $model = get_class(get_model_instance('MasterChild','DynamicMaster'));
        return $this->hasMany($model,'master_parent_id');
    }


}
