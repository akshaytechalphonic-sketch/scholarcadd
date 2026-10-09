<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected $table = 'tblcourse_category';

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
        $userModel = get_class(get_model_instance('Company', 'DynamicMaster'));

        return $this->belongsTo($userModel, 'company_id');
    }

    public function sub_category()
    {
        $userModel = get_class(get_model_instance('CourseCategory', 'Course'));
        return $this->belongsTo($userModel, 'main_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(CourseCategory::class, 'main_category_id');
    }

    public function children()
    {
        return $this->hasMany(CourseCategory::class, 'main_category_id');
    }
}
