<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class CourseQuizAttempt extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_EXPIRED = 'expired';

    protected $table = 'tbl_quiz_attempts';

    protected $guarded = [];

    protected $casts = [
        'total_questions' => 'integer',
        'attempted_count' => 'integer',
        'correct_count' => 'integer',
        'incorrect_count' => 'integer',
        'unanswered_count' => 'integer',
        'score_percentage' => 'float',
        'is_passed' => 'boolean',
        'duration_minutes' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(CourseQuiz::class, 'quiz_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(CourseQuizAttemptAnswer::class, 'attempt_id');
    }

    public function getRemainingSecondsAttribute(): int
    {
        if ($this->status !== self::STATUS_IN_PROGRESS || !$this->duration_minutes) {
            return 0;
        }

        $totalAllowedSeconds = $this->duration_minutes * 60;
        $elapsedSeconds = now()->diffInSeconds($this->started_at, false);
        $remaining = $totalAllowedSeconds - abs($elapsedSeconds);

        return max(0, (int) $remaining);
    }
}
