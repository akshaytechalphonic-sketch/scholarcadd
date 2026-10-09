<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_enrollments', function (Blueprint $table) {
            $table->id();
            $table->enum('enroll_type', ['myself', 'someone']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('country_code', 10);
            $table->string('phone', 20);
            $table->string('email');
            $table->string('alternate_contact')->nullable();
            $table->string('alternate_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_enrollments');
    }
};
