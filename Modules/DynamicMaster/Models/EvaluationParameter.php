<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationParameter extends Model
{
    use HasFactory;
    protected $table = "tbl_evaluation_parameters";
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
    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
    public function subparameter()
    {
        $model = get_class(get_model_instance('EvaluationSubParameter','DynamicMaster'));
        return $this->hasMany($model, 'evaluation_parameter_id');
    }
    public function subparameter_pr()
    {
        $model = get_class(get_model_instance('EvaluationSubParameter','DynamicMaster'));
        return $this->hasMany($model, 'evaluation_parameter_id')
                    ->where('type', 'Technical');
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }
}
