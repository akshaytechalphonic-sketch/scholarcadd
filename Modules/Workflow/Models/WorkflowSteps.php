<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkflowSteps extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_step';
    protected $guarded = [];

    public function user()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'created_by');
    }
    public function workflowCode_Name()
    {
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }

    public function setup(){
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }

    public function wf_assignment()
    {
        $model = get_class(get_model_instance('WorkflowAssignment','Workflow'));
        return $this->hasMany($model,'workflow_step_id');
    }
    public function conditions()
    {
        $model = get_class(get_model_instance('Workflow_Condition','Workflow'));
        return $this->hasMany($model,'workflow_step_id');
    }
    public function next_step()
    {
         $model = get_class(get_model_instance('WorkflowSteps','Workflow'));
        return $this->belongsTo($model, 'next_step_id');
    }
    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'ASC')->where('company_id',$cmp);
    }
}
