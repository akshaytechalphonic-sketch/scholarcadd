<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Workflow_MapLog extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_map_log';

    protected $guarded = [];

    public function user()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'created_by');
    }

    public function setup()
    {
        $model = get_class(get_model_instance('WorkflowSetup','Workflow'));
        return $this->belongsTo($model, 'workflow_setup_id');
    }
    public function scopeActiveOnly($query,$cmp)
    {
        return $query->orderBy('id', 'DESC')->where('company_id',$cmp);
    }
}
