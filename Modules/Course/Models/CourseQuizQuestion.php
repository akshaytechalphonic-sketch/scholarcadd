<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseQuizQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_quiz_questions';

    protected $guarded = [];

    protected $casts = [
        'order_no' => 'integer',
        'marks' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(CourseQuiz::class, 'quiz_id');
    }

    public function options()
    {
        return $this->hasMany(CourseQuizQuestionOption::class, 'question_id')->orderBy('order_no', 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
