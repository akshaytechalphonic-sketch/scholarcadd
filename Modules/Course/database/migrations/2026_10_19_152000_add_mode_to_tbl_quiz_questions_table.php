<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tbl_quiz_questions') && !Schema::hasColumn('tbl_quiz_questions', 'mode')) {
            Schema::table('tbl_quiz_questions', function (Blueprint $table) {
                $table->string('mode', 20)->default('both')->after('type'); // both, practice, exam
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tbl_quiz_questions') && Schema::hasColumn('tbl_quiz_questions', 'mode')) {
            Schema::table('tbl_quiz_questions', function (Blueprint $table) {
                $table->dropColumn('mode');
            });
        }
    }
};
