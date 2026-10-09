<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Course\Models\Course;

class ScheduleAppointment extends Model
{
    protected $table = 'tbl_schedule_appointments';
    protected $guarded = [];
    // protected $casts = [
    //     'agreed' => 'boolean',
    // ];




    // protected $fillable = [
    //     'full_name',
    //     'email',
    //     'phone',
    //     'message',
    //     'country_id',
    //     'course_id',
    //     'agreed',
    // ];

    protected $casts = [
        'full_name' => 'encrypted',
        'email' => 'encrypted',
        'phone' => 'encrypted',
        'message' => 'encrypted',
        'agreed' => 'boolean',
    ];



        public function country()
    {
        $userModel = get_class(get_model_instance('CourseCountry','Course'));
        return $this->belongsTo($userModel, 'country_id');
    }
        public function Coursedate()
    {
        $userModel = get_class(get_model_instance('Course', 'Course'));
        return $this->belongsTo($userModel, 'course_id');
    }






}
