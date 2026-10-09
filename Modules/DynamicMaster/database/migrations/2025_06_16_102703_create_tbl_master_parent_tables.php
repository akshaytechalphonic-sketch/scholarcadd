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
        Schema::create('tbl_master_parent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_type_id');
            $table->string('name');
            $table->string('code', 100);
            $table->string('description',255);

            $table->string('main_account',100)->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->enum('status', ['Active','Inactive']);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('tbl_employee')->onDelete('cascade');
            $table->foreign('master_type_id')->references('id')->on('tbl_master_type')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_master_parent');
    }
};
