<?php

namespace Modules\DynamicMaster\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table ="tbl_employee";
    protected $guarded = [];
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = encrypt($value);
    // }

    // public function getNameAttribute($value)
    // {
    //     return decrypt($value);
    // }
    // public function setPermanentAddressAttribute($value)
    // {
    //     $this->attributes['permanent_address'] = encrypt($value);
    // }
    // public function getPermanentAddressAttribute($value)
    // {
    //     return decrypt($value);
    // }

    // public function setCurrentAddressAttribute($value)
    // {
    //     $this->attributes['current_address'] = encrypt($value);
    // }
    // public function getCurrentAddressAttribute($value)
    // {
    //     return decrypt($value);
    // }


    // public function setCityAttribute($value)
    // {
    //     $this->attributes['city'] = encrypt($value);
    // }

    // /**
    //  * Decrypt the name when retrieving it
    //  */
    // public function getCityAttribute($value)
    // {
    //     return decrypt($value);
    // }

    // public function setStateAttribute($value)
    // {
    //     $this->attributes['state'] = encrypt($value);
    // }

    // /**
    //  * Decrypt the name when retrieving it
    //  */
    // public function getStateAttribute($value)
    // {
    //     return decrypt($value);
    // }

    // public function setZipCodeAttribute($value)
    // {
    //     $this->attributes['zip_code'] = encrypt($value);
    // }

    // /**
    //  * Decrypt the name when retrieving it
    //  */
    // public function getZipCodeAttribute($value)
    // {
    //     return decrypt($value);
    // }

    public function creator()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model,'created_by');
    }
    public function updator()
    {
        $model = get_class(get_model_instance('User'));
        return $this->belongsTo($model,'updated_by');
    }
    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model,'company_id');
    }
    public function country()
    {
        $model = get_class(get_model_instance('Country','DynamicMaster'));
        return $this->belongsTo($model,'country_id');
    }

    public function scopeActiveOnly($query,$cmp)
    {
        return $query->where('status', 'Active')->orderBy('id', 'DESC')->where('company_id',$cmp);
    }
     public function department()
    {
        $model = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($model, 'department_id');
    }


}
