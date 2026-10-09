<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyEvaluation extends Model
{
    use HasFactory;

    protected $table = "tbl_apply_evaluations";
    protected $guarded = [];
    protected $casts = [
        'role' => 'array',
    ];

    public function employee()
    {
        $model = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($model, 'emp_id');
    }
    public function quotation()
    {
        $model = get_class(get_model_instance('Quotation','DynamicMaster'));
        return $this->belongsTo($model, 'quotation_id');
    }

}
