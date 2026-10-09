<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Models\Course;

class Feature extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tbl_course_plan_features';


    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }

}
