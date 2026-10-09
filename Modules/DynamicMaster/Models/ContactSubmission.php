<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{

    protected $guarded = [];
    protected $table = 'contact_submissions';


    protected $casts = [
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
