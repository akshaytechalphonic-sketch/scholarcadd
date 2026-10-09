<?php

namespace Modules\Course\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_courses';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->url_title)) {
                $page->url_title = Str::slug($page->url_title);
            }
        });

        static::updating(function ($page) {
            if (empty($page->url_title)) {
                $page->url_title = Str::slug($page->url_title);
            }
        });
    }

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }

    public function course_image()
    {
        $userModel = get_class(get_model_instance('CourseImages', 'Course'));
        // return $this->hasMany($userModel, 'Course_id');
        return $this->hasMany(CourseImages::class, 'Course_id');
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

    public function course_category()
    {
        $userModel = get_class(get_model_instance('CourseCategory', 'Course'));
        return $this->belongsTo($userModel, 'course_category_id');
    }

    public function planMappings()
    {
        $mappingModel = get_class(get_model_instance('PlanMapping', 'DynamicMaster'));
        $basicPlan = get_model_instance('Plan' , 'DynamicMaster')->where('name', 'Basic plan')->first();

        if (!$basicPlan) {
            return $this->hasMany($mappingModel, 'course_id', 'id')->whereRaw('1=0');
        }

        return $this->hasMany($mappingModel, 'course_id', 'id')
                    ->where('plan_id', $basicPlan->id);
    }





    // relations made by shivam

    public function dynamicCoursePages()
    {
        return $this->hasMany(DynamicCoursePages::class);
    }

    public function keyFeatures()
    {
        return $this->hasMany(DynamicCourseKeyfeatures::class);
    }

    public function highlights()
    {
        return $this->hasMany(CourseHighlights::class);
    }

    public function sectionHeadings()
    {
        return $this->hasMany(DynamicCourseSectionheading::class);
    }

    public function examStructure()
    {
        return $this->hasMany(NewExamStructure::class);
    }

    public function headings()
    {
        return $this->hasMany(CourseHeading::class);
    }

    public function whatYouLearn()
    {
        return $this->hasMany(Course_Whatyoulearn::class);
    }

    public function overviews()
    {
        return $this->hasMany(CourseOverviews::class);
    }

    public function whyScholarAcad()
    {
        return $this->hasMany(CourseWhyScholarAcad::class);
    }

    public function supportPages()
    {
        return $this->hasMany(CourseSupportPage::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(CourseQuiz::class, 'course_id');
    }


    // public function countries()
    // {
    //     return $this->belongsToMany(CourseCountry::class, 'course_country_pivot', 'course_id', 'country_id');
    //     // if you have a pivot table linking courses to countries
    // }

     public function scopeFilterBy($query, $countryId, $stateId, $courseId)
    {
        return $query->when($courseId, fn($q) => $q->where('id', $courseId))
            ->when($countryId, fn($q) => $q->whereHas('countries', fn($q2) => $q2->where('id', $countryId)))
            ->when($stateId, fn($q) => $q->where('state_id', $stateId));
    }


}
