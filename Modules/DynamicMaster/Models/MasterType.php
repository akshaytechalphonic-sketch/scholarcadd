<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterType extends Model
{
    use HasFactory;

    protected $table ="tbl_master_type";
    protected $guarded = [];

    public function masterParent()
    {
         $model = get_class(get_model_instance('MasterPArent','DynamicMaster'));
        return $this->hasMany($model,'master_type_id');
    }



}
