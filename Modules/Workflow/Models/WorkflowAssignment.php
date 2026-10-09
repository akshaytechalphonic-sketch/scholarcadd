<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkflowAssignment extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_assignment';
    protected $guarded = [];

    public function assign_user()
    {
        $model = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($model, 'employee_id');
    }
    public function assign_dept()
    {
        $model = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($model, 'dept_id')
        ->whereHas('masterType', function ($query)  {
            $query->where('name', "Department");
        });
    }

    public function step_condition()
    {
        $model = get_class(get_model_instance('WorkflowSteps','Workflow'));
        return $this->belongsTo($model, 'workflow_step_id');
    }

    public function setup(){
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }
    public function steps()
    {
        $model = get_class(get_model_instance('WorkflowSteps','Workflow'));
        return $this->belongsTo($model, 'workflow_step_id');
    }
    public function user()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'created_by');
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }

}
