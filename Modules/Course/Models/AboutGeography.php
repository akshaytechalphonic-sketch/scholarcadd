<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutGeography extends Model
{
    use HasFactory;

     protected $guarded = [''];

    protected $table = 'tbl_about_geography';

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
        $userModel = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($userModel, 'company_id');
    }
    public function country()
    {
        $userModel = get_class(get_model_instance('CourseCountry','Course'));
        return $this->belongsTo($userModel, 'country_id');
    }
  
    public function state_date()
    {
        $userModel = get_class(get_model_instance('State','Course'));
        return $this->belongsTo($userModel, 'state_id');
    }
}
