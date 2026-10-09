<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tbl_course_quizzes') && !Schema::hasColumn('tbl_course_quizzes', 'passing_score')) {
            Schema::table('tbl_course_quizzes', function (Blueprint $table) {
                $table->integer('passing_score')->nullable()->after('duration_minutes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tbl_course_quizzes') && Schema::hasColumn('tbl_course_quizzes', 'passing_score')) {
            Schema::table('tbl_course_quizzes', function (Blueprint $table) {
                $table->dropColumn('passing_score');
            });
        }
    }
};
