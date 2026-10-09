<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('section_details', function (Blueprint $table) {
            $table->id();
            $table->string('section')->nullable();
            $table->string('label')->nullable();
            $table->string('heading')->nullable();
            $table->string('description', 200)->nullable();
            $table->string('button_label', 200)->nullable();

            // Image + Label pairs
            $table->string('image_one')->nullable();
            $table->string('label_one', 200)->nullable();

            $table->string('image_two')->nullable();
            $table->string('label_two', 200)->nullable();

            $table->string('image_three')->nullable();
            $table->string('label_three', 200)->nullable();

            $table->string('image_four')->nullable();
            $table->string('label_four', 200)->nullable();

            $table->string('image_five')->nullable();
            $table->string('label_five', 200)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_details');
    }
};
