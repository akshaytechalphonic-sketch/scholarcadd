<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Workflow_TransitionLine extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'tbl_workflow_transition_line';
    protected $guarded = [];

    public function requesterName(){
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'emp_id');
    }
    public function emp_name(){
        $model = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($model, 'emp_id');
    }

    public function workflowName()
    {
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }

    public function mapName(){
        $model = get_class(get_model_instance('WorkflowMap','Workflow'));
        return $this->belongsTo($model, 'workflow_map_id');
    }
    public function step_name()
    {
        $model = get_class(get_model_instance('WorkflowSteps','Workflow'));
        return $this->belongsTo($model,'workflow_steps_id');
    }
    public function head_data(){
        $model = get_class(get_model_instance('Workflow_TransitionHeader','Workflow'));
        return $this->belongsTo($model,'workflow_transition_header');
    }
    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }

}
