<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_whothis_course_banner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('record_one')->nullable();
            $table->string('record_two')->nullable();
            $table->string('record_three')->nullable();
            $table->string('record_four')->nullable();
            $table->string('record_five')->nullable();
            $table->string('record_six')->nullable();
            $table->text('read_more')->nullable();
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
        Schema::dropIfExists('tbl_whothis_course_banner');
    }
};
