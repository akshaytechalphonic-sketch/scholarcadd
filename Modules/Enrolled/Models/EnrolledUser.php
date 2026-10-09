<?php

namespace Modules\Enrolled\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledUser extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected $table = 'tbl_enrolled';

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }

    public function CourseImages()
    {
        $userModel = get_class(get_model_instance('CourseImages', 'Course'));
        return $this->hasMany($userModel, 'Course_id');
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

    public function courseName()
    {
        $userModel = get_class(get_model_instance('Course', 'Course'));
        return $this->belongsTo($userModel, 'course_id');
    }
    public function countryName()
    {
        $userModel = get_class(get_model_instance('CourseCountry', 'Course'));
        return $this->belongsTo($userModel, 'country_id');
    }
}
