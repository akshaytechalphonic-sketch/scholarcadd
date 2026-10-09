<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkflowDeligation extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_deligation';
    protected $guarded = [];

    public function user_name()
    {
        $model = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($model, 'emp_from');
    }
    public function assign_to()
    {
        $model = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($model, 'emp_to');
    }
    public function map_name()
    {
        $model = get_class(get_model_instance('WorkflowMap','Workflow'));
        return $this->belongsTo($model, 'workflow_id');
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }

}
