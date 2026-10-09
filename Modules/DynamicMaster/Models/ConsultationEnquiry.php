<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationEnquiry extends Model
{
    protected $table = 'tbl_consultation_enquiries';
    protected $guarded = [];



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

}
