<?php

namespace Modules\Course\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseCountry extends Model
{
    use HasFactory;

    protected $guarded = [''];
    protected $table = 'tbl_course_country';

            protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->url)) {
                $page->url = Str::slug($page->url);
            }
        });

        static::updating(function ($page) {
            if (empty($page->url)) {
                $page->url = Str::slug($page->url);
            }
        });
    }

    public function creator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'created_by');
    }
    public function updator()
    {
        $userModel = get_class(get_model_instance('User'));
        return $this->belongsTo($userModel, 'updated_by');
    }

    public function company()
    {
        $userModel = get_class(get_model_instance('Company','DynamicMaster'));
        return $this->belongsTo($userModel, 'company_id');
    }
    public function locationType()
    {
        $userModel = get_class(get_model_instance('MasterParent','DynamicMaster'));
        return $this->belongsTo($userModel, 'location_type');
    }
}
