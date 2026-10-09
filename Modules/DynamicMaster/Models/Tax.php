<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_tax';
    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
    public function taxType()
    {
        $taxTypeModel = get_class(get_model_instance('TaxType','DynamicMaster'));
        return $this->belongsTo($taxTypeModel, 'tax_type_id');
    }
    public function scopeActiveOnly($query)
    {
        return $query->where('is_active', 1);
    }

}
