<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationGroup extends Model
{
    use HasFactory;
    protected $table = "tbl_evaluation_groups";
    protected $guarded = [];
    public function creator()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'created_by');
    }
    public function updator()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'updated_by');
    }


    public function evalutionmember()
    {
        $model = get_class(get_model_instance('EvaluationMember','DynamicMaster'));
        return $this->hasMany($model, 'evaluation_group_id');
    }
    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
    public function evaluation_parameter()
    {
        $model = get_class(get_model_instance('EvaluationParameter','DynamicMaster'));
        return $this->belongsTo($model, 'evaluation_parameter_id');
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }
}
