<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class LearningCalender extends Model
{
    protected $table = 'tbl_learning_calenders';
    protected $guarded = [];
    protected $casts = [
            'agree_terms' => 'boolean',
            'full_name' => 'encrypted',
            'email' => 'encrypted',
            'contact_number' => 'encrypted',
            'message' => 'encrypted',
            'agreed' => 'boolean',
        ];

    public function Coursedate()
    {
        $userModel = get_class(get_model_instance('Course', 'Course'));
        return $this->belongsTo($userModel, 'course')->select('id','course_title');
    }
    public function CourseCountryName()
    {
        $userModel = get_class(get_model_instance('CourseCountry', 'Course'));
        return $this->belongsTo($userModel, 'country')->select('id','name');
    }
   
}
