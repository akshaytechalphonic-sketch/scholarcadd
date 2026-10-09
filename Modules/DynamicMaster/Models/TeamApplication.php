<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCountry;

class TeamApplication extends Model
{
    protected $table = 'tbl_team_applications';
    protected $guarded = [];

    protected $casts = [
        'uploaded_files' => 'array',
        'agree' => 'boolean',
        'agree_terms' => 'boolean',
        'full_name' => 'encrypted',
        'email' => 'encrypted',
        'phone' => 'encrypted',
        'message' => 'encrypted',
        'agreed' => 'boolean',
    ];

    public function CourseCountryName()
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
