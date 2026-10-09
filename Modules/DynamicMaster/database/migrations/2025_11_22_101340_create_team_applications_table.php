<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_team_applications', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('training_expertise')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('training_delivery_mode')->nullable();
            $table->enum('ready_to_travel', ['Yes', 'No'])->nullable();
            $table->enum('preferred_option', ['Full Time', 'Part Time'])->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->json('uploaded_files')->nullable();
            $table->boolean('agree')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_team_applications');
    }
};
