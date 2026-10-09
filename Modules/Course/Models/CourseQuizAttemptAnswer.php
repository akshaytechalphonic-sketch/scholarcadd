<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $table = 'tbl_quiz_attempt_answers';

    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
        'is_flagged' => 'boolean',
        'time_spent_seconds' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(CourseQuizAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(CourseQuizQuestion::class, 'question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(CourseQuizQuestionOption::class, 'selected_option_id');
    }
}
