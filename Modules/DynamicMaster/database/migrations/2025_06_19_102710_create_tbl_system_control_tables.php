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
        Schema::create('tbl_system_control', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('otptime', 100)->nullable();
            $table->boolean('otp_enable')->nullable();
            $table->enum('two_fa_enable_employee', ['Yes', 'No'])->default('Yes');
            $table->string('number_of_otp_attempt', 100)->nullable();
            $table->enum('workflow_type', ['Consolidated', 'Company Wise'])->default('Consolidated');
            $table->enum('numbersequence_type', ['Consolidated', 'Company Wise'])->default('Consolidated');
            $table->enum('quotation_evaluation_type', ['Complete', 'Line Wise'])->default('Line Wise');
            $table->enum('rfi_evaluation_type', ['Complete', 'Line Wise'])->default('Line Wise');
            $table->boolean('mailSetting')->nullable();
            $table->string('base_url', 100);
            $table->string('resource_path', 100);
            $table->string('host', 100)->nullable();
            $table->string('port', 100)->nullable();
            $table->string('username', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('encryption', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->unsignedBigInteger('default_currency_id')->nullable();

            $table->integer('auto_release_time')->nullable();
            $table->integer('auto_release_enable')->nullable();
            $table->integer('password_expiry')->nullable();
            $table->integer('password_expiry_enable')->nullable();
            $table->integer('default_company_enable')->nullable();
            $table->unsignedBigInteger('default_company')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_system_control');
    }
};
