<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkflowSetup extends Model
{
    use HasFactory;

    protected $table = 'tbl_workflow_setup';
    protected $guarded = [];

    public function user()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'created_by');
    }
    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }
    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
    public function workflow_map()
    {
        $model = get_class(get_model_instance('WorkflowMap','Workflow'));
        return $this->belongsTo($model, 'workflow_map_id');
    }

    public function apply_on()
    {
        $model = get_class(get_model_instance('WorkflowMap','Workflow'));
        return $this->belongsTo($model, 'apply_on_id');
    }


}
