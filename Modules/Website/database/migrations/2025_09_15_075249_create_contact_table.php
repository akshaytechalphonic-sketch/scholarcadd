<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       // Pages Table
        // ----------------------
        Schema::create('tbl_contact', function (Blueprint $table) {
            $table->id();
            $table->string('address');                   // Page title
            $table->bigInteger('phone')->unique();          // URL-friendly slug
            $table->string('email')->nullable();       // Page content (HTML/text)
            $table->string('Hours')->nullable();  // SEO meta title
            $table->string('meta_description')->nullable(); // SEO meta description
            $table->enum('status',["Active", "In Active"])->default("Active");  // Active/Inactive
            $table->string('contact_img', 255)->nullable();
            $table->string('map', 255)->nullable();

            /// Social media urls

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('x')->nullable();
            $table->string('linkedin')->nullable();

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
        Schema::dropIfExists('tbl_contact');
    }
};
