<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DynamicCoursePages extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [''];
    protected $table = 'tbl_dynamic_course_pages';

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
        $userModel = get_class(get_model_instance('Company', 'DynamicMaster'));
        return $this->belongsTo($userModel, 'company_id');
    }

    public function coursedate()
    {
        // $userModel = get_class(get_model_instance('Course', 'Course'));
        return $this->belongsTo(Course::class, 'course_id');
        // return $this->belongsTo($userModel, 'course_id');
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


    // relation made by Shivam
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
