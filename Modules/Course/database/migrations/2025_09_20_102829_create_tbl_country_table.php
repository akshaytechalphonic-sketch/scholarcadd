<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_course_country', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('continent_id')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->enum('isPage',['No', 'Yes'])->nullable();
            $table->string('iso3', 10)->nullable();
            $table->string('numeric_code',10)->nullable();
            $table->string('iso2', 10)->nullable();
            $table->string('phonecode', 50)->nullable();
            $table->string('capital', 50)->nullable();
            $table->string('currency', 50)->nullable();
            $table->decimal('value_in_dollar', 10, 2)->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('tld')->nullable();
            $table->string('native')->nullable();
            $table->string('region')->nullable();
            $table->string('regisubregionon')->nullable();
            $table->text('timezones')->nullable();
            $table->text('translations')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->string('emoji')->nullable();
            $table->string('emojiU')->nullable();
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
        Schema::dropIfExists('tbl_country');
    }
};
