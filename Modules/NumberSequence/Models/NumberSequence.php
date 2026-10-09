<?php

namespace Modules\NumberSequence\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumberSequence extends Model
{
    use HasFactory;

    protected $table ="tbl_number_sequence";
    protected $guarded = [];

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }

    public function scopeActiveOnly($query,$cmp_id = null)
    {
        if($cmp_id){
            return $query->where('status', 'Active')->orderBy('id', 'DESC')->where('company_id',$cmp_id);
        }
        return $query->where('status', 'Active')->orderBy('id', 'DESC');
    }

    public function updator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
    public function company()
    {
         $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }

    public function numbersequence_map()
    {
         $model = get_class(get_model_instance('NumberSequenceMap','NumberSequence'));
        return $this->belongsTo($model, 'numbersequence_map_id');
    }


}
