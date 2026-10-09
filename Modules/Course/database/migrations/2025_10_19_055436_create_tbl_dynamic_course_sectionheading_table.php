<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_dynamic_course_sectionheading', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('program_overview')->nullable();
            $table->string('training_highlights')->nullable();
            $table->string('advantage')->nullable();
            $table->string('new_learning_objectives')->nullable();
            $table->string('old_learning_objectives')->nullable();
            $table->string('curriculum')->nullable();
            $table->string('exam_structure')->nullable();
            $table->string('audience')->nullable();
            $table->string('eligibility')->nullable();
            $table->string('salaries')->nullable();
            $table->string('training_options')->nullable();
            $table->string('schedule')->nullable();
            $table->string('path')->nullable();
            $table->string('trainers')->nullable();
            $table->string('certifications')->nullable();
            $table->string('testimonials')->nullable();
            $table->string('course_benefits')->nullable();
            $table->string('location_faqs')->nullable();
            $table->string('faqs')->nullable();
            $table->string('blogs')->nullable();
            $table->string('about_geography')->nullable();

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
        Schema::dropIfExists('dynamic_course_sectionheading');
    }
};
