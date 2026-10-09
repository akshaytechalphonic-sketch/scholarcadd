<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_courses', function (Blueprint $table) {
            $table->id();
            $table->string('is_demand_course')->nullable();
            $table->string('url_title')->nullable();
            $table->string('course_short_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->string('course_title')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('overview')->nullable();
            $table->text('header_image')->nullable();
            $table->text('banner_bg_color')->nullable();
            $table->longText('overview1')->nullable();
            $table->longText('training_options')->nullable();
            $table->longText('course_curriculum')->nullable();
            $table->longText('course_details')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('page_title')->nullable();
               // Foreign keys
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            // Constraints
            $table->foreign('course_category_id')->references('id')->on('tblcourse_category')->onDelete('cascade');


            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_Course_Courses');
    }
};
