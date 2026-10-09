<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_dynamic_course_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('is_ready')->nullable();
                 // Foreign keys
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            // Constraints
            $table->foreign('course_id')->references('id')->on('tbl_courses')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('tbl_course_country')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('tbl_state')->onDelete('cascade');

            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_dynamic_course_pages');
    }
};
