<?php

namespace Modules\NumberSequence\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumberSequenceMap extends Model
{
    use HasFactory;

    protected $table ="tbl_number_sequence_map";
    protected $guarded = [];

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }


    public function updator()
    {
         $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'updated_by');
    }
    public function company()
    {
         $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }

    public function number_sequence()
    {
         $model = get_class(get_model_instance('NumberSequence','NumberSequence'));
        return $this->hasOne($model, 'numbersequence_map_id');
    }

    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'Active')->orderBy('id', 'DESC');
    }


}
