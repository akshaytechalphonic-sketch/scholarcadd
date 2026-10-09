<?php

namespace Modules\Workflow\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Workflow_TransitionHeader extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_transition_header';
    protected $guarded = [];

    public function workflowName()
    {
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }
    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }

    public function mapName(){
        $model = get_class(get_model_instance('WorkflowMap','Workflow'));
        return $this->belongsTo($model, 'workflow_map_id');
    }
    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }

}
