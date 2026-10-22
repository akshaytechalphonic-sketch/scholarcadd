<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_dynamic_course_headline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('page_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->string('hone_tag')->nullable();
            $table->string('imageAltText')->nullable();
            $table->text('metaDescription')->nullable();
            $table->text('imageAltDescription')->nullable();
                 // Foreign keys
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            // Constraints
            $table->foreign('course_id')->references('id')->on('tbl_courses')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_dynamic_course_headline');
    }
};
