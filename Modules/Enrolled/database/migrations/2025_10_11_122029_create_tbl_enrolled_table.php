<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_enrolled', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longtext('course_data')->nullable();
            $table->unsignedBigInteger('total_course_fees')->nullable();
            $table->enum('payment_done',['yes','no'])->nullable();
            $table->string('currency')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->text('checkout_session')->nullable();
            $table->text('invoice_link')->nullable();
            $table->text('invoice_download_link')->nullable();
            $table->text('invoice')->nullable();
                 // Foreign keys
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tbl_enrolled');
    }
};
