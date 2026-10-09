<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_company', function (Blueprint $table) {
            $table->id(); // bigint unsigned primary key
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('vat_no', 100)->nullable();
            $table->string('address', 255);
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('company_logo', 100)->nullable();
            $table->string('about', 255)->nullable();
            $table->string('registration_no', 100);
            $table->string('website', 255)->nullable();
            $table->string('payment_alias_name', 20);
            $table->date('origin_date');
            $table->string('contact_number', 20)->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Blocked']);
            $table->enum('default_company', ['Yes', 'No'])->default('No');
            $table->text('loa')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('tbl_country')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_company');
    }
};
