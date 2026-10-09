<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_bank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_group_id');
            $table->string('name')->nullable();
            $table->string('account_no')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('gl_account_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no', 20)->nullable();
            $table->string('iban')->nullable();
            $table->text('description')->nullable();
            $table->enum('enable_reconciliation',['Yes','No'])->default('No');
            $table->enum('status',['Active','In active'])->default('Active');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_bank');
    }
};
