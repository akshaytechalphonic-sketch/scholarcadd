<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseHighlights extends Model
{
    use HasFactory;

    protected $guarded = [''];

    protected $table = 'tbl_course_highlights';

    protected $casts = [
        'bulletArray' => 'array',
    ];
}
