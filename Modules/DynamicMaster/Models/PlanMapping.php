<?php

namespace Modules\DynamicMaster\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCountry;

class PlanMapping extends Model
{
    protected $table = 'tbl_plan_mappings';
    protected $guarded = [];


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function country()
    {
        return $this->belongsTo(CourseCountry::class, 'country_id');
    }

        public function features()
    {
        return $this->hasMany(Feature::class, 'plan_id', 'plan_id')
                    ->where('is_excluded', 0);
    }


}
