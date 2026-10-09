<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_plan_mappings', function (Blueprint $table) {
            $table->json('included_features')->nullable()->after('course_id');
            $table->enum('recommended', ['yes', 'no'])->default('no');


        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_plan_mappings', function (Blueprint $table) {
            $table->dropColumn('included_features');
            $table->dropColumn('recommended');
        });
    }
};
