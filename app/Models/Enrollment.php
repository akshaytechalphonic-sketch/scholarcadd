<?php

namespace App\Models;
use Modules\DynamicMaster\Models\Plan;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'tbl_enrollments';
    protected $guarded = [];

    public function coursedate()
    {
        $userModel = get_class(get_model_instance('Course', 'Course'));
        return $this->belongsTo($userModel, 'course_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function country()
    {
        $userModel = get_class(get_model_instance('CourseCountry','Course'));
        return $this->belongsTo($userModel, 'country_code');
    }

            public function schedule()
    {
        $scheduleModel = get_class(get_model_instance('CourseSchedule','Course'));
            return $this->belongsTo(
        $scheduleModel,
        'schedule_id',
        'course_schedule_id'
    );
    }
}

