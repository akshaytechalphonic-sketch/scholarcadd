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
        Schema::create('tbl_request_for_services', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('message')->nullable()->nullable();
            $table->string('service', 100)->nullable();

            $table->boolean('accepted_terms')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_request_for_services');
    }
};
