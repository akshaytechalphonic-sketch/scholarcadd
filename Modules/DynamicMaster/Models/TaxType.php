<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxType extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_tax_type';
    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
    public function Tax()
    {
        $taxModel = get_class(get_model_instance('Tax','DynamicMaster'));
        return $this->hasMany($taxModel, 'tax_type_id');
    }
}
