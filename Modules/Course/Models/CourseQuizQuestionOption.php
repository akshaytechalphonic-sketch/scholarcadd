<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuizQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'tbl_quiz_question_options';

    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
        'order_no' => 'integer',
    ];

    public function question()
    {
        return $this->belongsTo(CourseQuizQuestion::class, 'question_id');
    }
}
