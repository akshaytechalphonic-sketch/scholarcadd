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
        Schema::create('tbl_workflow_deligation', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->unsignedBigInteger('emp_from');
            $table->unsignedBigInteger('emp_to');
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('tbl_workflow_deligation');
    }
};
