<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSubParameter extends Model
{
    use HasFactory;
    protected $table = "tbl_evaluation_subparameters";
    protected $guarded = [];

    public function evaluation_parameter()
    {
        $model = get_class(get_model_instance('EvaluationParameter','DynamicMaster'));
        return $this->belongsTo($model, 'evaluation_parameter_id');
    }

}
