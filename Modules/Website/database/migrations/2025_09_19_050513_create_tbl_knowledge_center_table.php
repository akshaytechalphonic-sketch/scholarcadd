<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_knowledge_center', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->string('course_id')->nullable();
            $table->string('url_title');        // Page title
            $table->unsignedBigInteger('resource_category')->nullable();
            $table->string('resource_title')->nullable();
            $table->text('header_image')->nullable();          // URL-friendly slug
            $table->string('resource_auther')->nullable();  // SEO meta title
            $table->text('short_description')->nullable(); // SEO meta description
            $table->longText('overview')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable(); // SEO meta description
            $table->string('page_title')->nullable();
            $table->string('user_views')->nullable();
            $table->enum('status',['Active', 'In Active'])->default('Active');  // Active/Inactive
          // Page content (HTML/text)

            // Foreign keys
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('tbl_knowledge_center');
    }
};
