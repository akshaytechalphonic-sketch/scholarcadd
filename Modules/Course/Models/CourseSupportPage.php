<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSupportPage extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_SYLLABUS = 'syllabus';
    public const TYPE_EXAM_FORMAT = 'exam-format';
    public const STATUS_PUBLISHED = 'published';

    protected $guarded = [];

    protected $table = 'tbl_course_support_pages';

    protected $casts = [
        'is_indexable' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
