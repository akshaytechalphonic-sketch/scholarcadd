<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ----------------------
        // Pages Table
        // ----------------------
        Schema::create('tbl_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');                   // Page title
            $table->string('slug')->unique();          // URL-friendly slug
            $table->text('content')->nullable();       // Page content (HTML/text)
            $table->string('meta_title')->nullable();  // SEO meta title
            $table->string('meta_description')->nullable(); // SEO meta description
            $table->boolean('status')->default(true);  // Active/Inactive
            $table->string('pages_img', 255)->nullable();

            // Foreign keys
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            // Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('tbl_country')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        // ----------------------
        // Blogs Table
        // ----------------------
        Schema::create('tbl_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');                   // Blog title
            $table->string('slug')->unique();          // URL-friendly slug
            $table->text('content')->nullable();       // Blog content
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('status')->default(true);
            $table->string('pages_img', 255)->nullable();

            // Foreign keys
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            // Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('tbl_country')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop blogs first because it may depend on pages
        Schema::dropIfExists('tbl_blogs');
        Schema::dropIfExists('tbl_pages');
    }
};
