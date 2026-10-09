<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_location', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('location_name')->nullable();
            $table->enum('location_type',['Warehouse','Store','Distribution Center'])->nullable();
            $table->text('address_line1')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('gps_latitude')->nullable();
            $table->string('gps_longitude')->nullable();
             $table->unsignedBigInteger('company_id')->nullable();
            $table->enum('status',['Active','In active'])->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_Course_location');
    }
};
