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
            $table->id();
            $table->unsignedBigInteger('company_id')->unique();
            $table->boolean('dark_mode')->default(false);
            $table->boolean('rtl')->default(false);
            $table->boolean('enable_system_notification')->default(false);
            $table->boolean('enable_email_notification')->default(false); 
            $table->enum('time_format', ['12', '24'])->default('12');
            $table->enum('currency_symbol', ['name', 'code'])->default('code');
            $table->enum('def_language', ['EN', 'AR'])->default('EN'); 
            $table->enum('enable_google_auth', ['Yes', 'No'])->default('Yes');
            $table->enum('enable_copy_paste', ['Yes', 'No'])->default('Yes');
            $table->enum('two_fa_enable_employee', ['Yes', 'No'])->default('Yes');
            $table->enum('invoice_cancle_allow', ['Yes', 'No'])->default('Yes');
            $table->enum('grn_cancle_allow', ['Yes', 'No'])->default('Yes');
            $table->string('date_format')->nullable();
            $table->string('time_zone')->nullable(); 
            $table->string('doc_format')->nullable(); 
            $table->string('light_logo')->nullable(); 
            $table->string('brand_name')->nullable(); 
            $table->string('small_logo')->nullable(); 
            $table->double('doc_max_size',2,1)->nullable(); 
            $table->enum('workflow_type', ['Consolidated', 'Company Wise'])->default('Consolidated');
            $table->enum('numbersequence_type', ['Consolidated', 'Company Wise'])->default('Consolidated');
            $table->unsignedBigInteger('default_currency_id')->nullable();
            $table->unsignedBigInteger('default_period_id')->nullable();            
            $table->string('mail_mailer',10)->nullable();
            $table->string('mail_host',50)->nullable();
            $table->string('mail_port',10)->nullable(); 
            $table->string('mail_user_name',50)->nullable();
            $table->string('mail_password',50)->nullable();
            $table->string('mail_encryption',10)->nullable(); 
            $table->string('mail_from_name',50)->nullable();
            $table->string('mail_from_address',50)->nullable();            
            $table->boolean('auto_release_enable')->default(false);
            $table->double('auto_release_time',4,0)->nullable();
            $table->boolean('password_expiry_enable')->default(false);
            $table->double('password_expiry_days',4,0)->nullable();
            $table->boolean('default_company_enable')->default(false);
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

 