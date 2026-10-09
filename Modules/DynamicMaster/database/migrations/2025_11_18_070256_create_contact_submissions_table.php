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
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('fullName');
            $table->string('enquiryType')->nullable();
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('course')->nullable();
            $table->string('phone')->nullable();
            $table->string('deliveryMode')->nullable();
            $table->string('contactMode')->nullable();
            $table->text('message')->nullable();
            $table->string('agree','20')->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }

};
