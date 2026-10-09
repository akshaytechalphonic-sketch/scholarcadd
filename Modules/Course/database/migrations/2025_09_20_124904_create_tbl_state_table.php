<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_state', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->enum('isPage',['No', 'Yes'])->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('country_code',10)->nullable();
            $table->string('fips_code')->nullable();
            $table->string('iso2', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->nullable()->comment('Rapid API GeoDB Cities');
            $table->timestamps();

             // Foreign keys

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            // Constraints
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_state');
    }
};
