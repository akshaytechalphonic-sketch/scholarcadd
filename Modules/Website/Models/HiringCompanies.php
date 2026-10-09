<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HiringCompanies extends Model
{
    use HasFactory;
    protected $guarded = [''];
    protected $table = 'tbl_hiring_companies';
    // public function scopeActiveOnly($query)
    // {
    //     return $query->where('status', 'Approved');
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($blog) {
    //         if (empty($blog->url_title)) {
    //             $blog->url_title = Str::slug($blog->resource_title);
    //         }
    //     });

    //     static::updating(function ($blog) {
    //         if (empty($blog->url_title)) {
    //             $blog->url_title = Str::slug($blog->resource_title);
    //         }
    //     });
    // }

    public function company()
    {
        $model = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($model, 'company_id');
    }
    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
    public function tags()
    {
        $model = get_class(get_model_instance('Tags','Website'));
        return $this->belongsToMany($model, 'knowledg_center_id');
    }

    public function Coursedate()
    {
        $userModel = get_class(get_model_instance('Course', 'Course'));
        return $this->belongsTo($userModel, 'course_id');
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
}
