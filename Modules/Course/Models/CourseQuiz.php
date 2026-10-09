<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseQuiz extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'tbl_course_quizzes';

    protected $guarded = [];

    protected $casts = [
        'total_questions' => 'integer',
        'duration_minutes' => 'integer',
        'passing_score' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function questions()
    {
        return $this->hasMany(CourseQuizQuestion::class, 'quiz_id')->orderBy('order_no', 'asc');
    }
}
