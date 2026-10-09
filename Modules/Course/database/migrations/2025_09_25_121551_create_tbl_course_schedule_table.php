<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_course_schedule', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('course_schedule_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('continent_id')->nullable();
            $table->unsignedBigInteger('course_category_id')->nullable();

            $table->date('class_start_date')->nullable();
            $table->date('class_end_date')->nullable();
            $table->date('class_start_time')->nullable();
            $table->date('class_end_time')->nullable();
            $table->string('class_language')->nullable();
            $table->text('course_includes')->nullable();
            $table->string('discount_amt')->nullable();
            $table->text('discount_offer_for')->nullable();

            $table->date('discount_end_date')->nullable();

            $table->datetime('enroll_end_date')->nullable();
            $table->enum('status', ['Public', 'Private', ''])->nullable();
            $table->unsignedBigInteger('course_fees')->nullable();
            $table->string('fee_currency')->nullable();
                // Foreign keys
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            // Constraints
            $table->foreign('course_id')->references('id')->on('tbl_courses')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('tbl_course_country')->onDelete('cascade');
            $table->foreign('course_category_id')->references('id')->on('tblcourse_category')->onDelete('cascade');


            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_course_schedule');
    }
};
