<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
     Schema::create('tbl_slider', function (Blueprint $table) {
    $table->id();
    $table->string('sub_heading')->nullable();
    $table->string('heading')->nullable();
    $table->string('main_content')->nullable();
    $table->string('section_image')->nullable();
    $table->string('label_one')->nullable();
    $table->string('label_one_counter')->nullable();
    $table->string('label_two')->nullable();
    $table->string('label_two_counter')->nullable();
    $table->json('slider_images')->nullable();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->unsignedBigInteger('updated_by')->nullable();
    $table->unsignedBigInteger('company_id')->nullable();
    $table->timestamps();


    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_slider');
    }
};
