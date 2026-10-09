<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_registration_request', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['Sent','Otp Matched','Blocked']);
            $table->integer('no_of_attempt')->nullable();
            $table->string('otp')->nullable();
            $table->integer('resend_otp')->nullable();
            $table->bigInteger('mobile_no')->nullable();
            $table->string('verify_email')->nullable();
            $table->string('verify_phone')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_registration_request');
    }
};
