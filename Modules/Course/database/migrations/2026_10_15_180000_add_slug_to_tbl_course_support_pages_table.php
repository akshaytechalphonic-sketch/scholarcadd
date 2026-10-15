<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tbl_course_support_pages') && !Schema::hasColumn('tbl_course_support_pages', 'slug')) {
            Schema::table('tbl_course_support_pages', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tbl_course_support_pages') && Schema::hasColumn('tbl_course_support_pages', 'slug')) {
            Schema::table('tbl_course_support_pages', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
