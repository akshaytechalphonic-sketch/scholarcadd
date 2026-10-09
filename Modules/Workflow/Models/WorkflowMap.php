<?php

namespace Modules\Workflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkflowMap extends Model
{
    use HasFactory;
    protected $table = 'tbl_workflow_map';
    protected $guarded = [];

}
