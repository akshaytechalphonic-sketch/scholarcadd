<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationMembers extends Model
{
    use HasFactory;
    protected $table = "tbl_evaluation_members";
    protected $guarded = [];
    protected $casts = [
        'role' => 'array',
    ];
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
    public function employee()
    {
        $model = get_class(get_model_instance('Company_employee','DynamicMaster'));
        return $this->belongsTo($model, 'company_employee_id');
    }

    public function evaluation_group()
    {
        $model = get_class(get_model_instance('EvaluationGroup','DynamicMaster'));
        return $this->belongsTo($model, 'evaluation_group_id');
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }
}
