<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('tbl_course_what_you_will_learn')) {
            Schema::table('tbl_course_what_you_will_learn', function (Blueprint $table) {
                if (!Schema::hasColumn('tbl_course_what_you_will_learn', 'pointers')) {
                    $table->longText('pointers')->nullable()->after('description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tbl_course_what_you_will_learn')) {
            Schema::table('tbl_course_what_you_will_learn', function (Blueprint $table) {
                if (Schema::hasColumn('tbl_course_what_you_will_learn', 'pointers')) {
                    $table->dropColumn('pointers');
                }
            });
        }
    }
};
