<?php

namespace Modules\Workflow\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Workflow_Condition extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_condition';
    protected $guarded = [];

    public function setup(){
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }
    public function steps()
    {
        $model = get_class(get_model_instance('WorkflowSteps','Workflow'));
        return $this->belongsTo($model, 'workflow_step_id');
    }

}
