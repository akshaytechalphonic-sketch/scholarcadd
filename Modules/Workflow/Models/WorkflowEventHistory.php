<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkflowEventHistory extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_event_history';
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


}
