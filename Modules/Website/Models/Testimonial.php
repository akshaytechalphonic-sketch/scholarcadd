<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected $table = 'tbl_testimonial';
    // public function scopeActiveOnly($query)
    // {
    //     return $query->where('status', 'Approved');
    // }

    public function company()
    {
        $model = get_class(get_model_instance('Company', 'DynamicMaster'));

        return $this->belongsTo($model, 'company_id');
    }

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));

        return $this->belongsTo($userModel, 'created_by');
    }

    public function Coursedate()
    {
        $userModel = get_class(get_model_instance('Course', 'Course'));

        return $this->belongsTo($userModel, 'course_id');
    }

    public function CourseCountryName()
    {
        $userModel = get_class(get_model_instance('CourseCountry', 'Course'));

        return $this->belongsTo($userModel, 'country_id');
    }

    public function StateName()
    {
        $userModel = get_class(get_model_instance('State', 'Course'));

        return $this->belongsTo($userModel, 'state_id');
    }
}
