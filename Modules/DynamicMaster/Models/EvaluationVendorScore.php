<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationVendorScore extends Model
{
    use HasFactory;
    protected $table = "tbl_evaluation_vendor_scores";
    protected $guarded = [];

    public function creator()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'created_by');
    }
    public function employee()
    {
        $model = get_class(get_model_instance('Employee','DynamicMaster'));
        return $this->belongsTo($model, 'emp_id');
    }
    public function updator()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model, 'updated_by');
    }
    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
}
