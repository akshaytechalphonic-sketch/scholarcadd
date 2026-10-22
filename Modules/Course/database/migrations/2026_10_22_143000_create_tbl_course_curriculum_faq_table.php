<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tbl_course_curriculum_faq')) {
            Schema::create('tbl_course_curriculum_faq', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('course_id')->references('id')->on('tbl_courses')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_course_curriculum_faq');
    }
};
