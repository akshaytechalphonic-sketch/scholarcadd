<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tbl_quiz_attempts')) {
            Schema::create('tbl_quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('quiz_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('session_id')->nullable();
                $table->string('mode', 30)->default('exam'); // exam, practice
                $table->string('status', 30)->default('in_progress'); // in_progress, completed, expired
                $table->integer('total_questions')->default(0);
                $table->integer('attempted_count')->default(0);
                $table->integer('correct_count')->default(0);
                $table->integer('incorrect_count')->default(0);
                $table->integer('unanswered_count')->default(0);
                $table->decimal('score_percentage', 5, 2)->default(0.00);
                $table->boolean('is_passed')->default(false);
                $table->timestamp('started_at')->useCurrent();
                $table->timestamp('completed_at')->nullable();
                $table->integer('duration_minutes')->default(0);

                $table->foreign('quiz_id')->references('id')->on('tbl_course_quizzes')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tbl_quiz_attempt_answers')) {
            Schema::create('tbl_quiz_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('attempt_id');
                $table->unsignedBigInteger('question_id');
                $table->unsignedBigInteger('selected_option_id')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->boolean('is_flagged')->default(false);
                $table->integer('time_spent_seconds')->default(0);

                $table->unique(['attempt_id', 'question_id']);
                $table->foreign('attempt_id')->references('id')->on('tbl_quiz_attempts')->onDelete('cascade');
                $table->foreign('question_id')->references('id')->on('tbl_quiz_questions')->onDelete('cascade');
                $table->foreign('selected_option_id')->references('id')->on('tbl_quiz_question_options')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_quiz_attempt_answers');
        Schema::dropIfExists('tbl_quiz_attempts');
    }
};
