<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tbl_quiz_questions')) {
            Schema::create('tbl_quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('quiz_id');
                $table->longText('question_text');
                $table->string('type', 50)->default('single_choice'); // single_choice, multiple_choice, etc.
                $table->string('image_url')->nullable();
                $table->longText('explanation')->nullable();
                $table->integer('order_no')->default(1);
                $table->integer('marks')->default(1);
                $table->string('status', 20)->default('active'); // active, inactive

                $table->foreign('quiz_id')->references('id')->on('tbl_course_quizzes')->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tbl_quiz_question_options')) {
            Schema::create('tbl_quiz_question_options', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('question_id');
                $table->text('option_text');
                $table->string('option_label', 10)->nullable(); // A, B, C, D, etc.
                $table->string('image_url')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('order_no')->default(1);

                $table->foreign('question_id')->references('id')->on('tbl_quiz_questions')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_quiz_question_options');
        Schema::dropIfExists('tbl_quiz_questions');
    }
};
