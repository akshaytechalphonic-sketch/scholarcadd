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
        if (Schema::hasTable('tbl_course_support_pages') && !Schema::hasColumn('tbl_course_support_pages', 'image')) {
            Schema::table('tbl_course_support_pages', function (Blueprint $table) {
                $table->string('image')->nullable()->after('content');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tbl_course_support_pages') && Schema::hasColumn('tbl_course_support_pages', 'image')) {
            Schema::table('tbl_course_support_pages', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
