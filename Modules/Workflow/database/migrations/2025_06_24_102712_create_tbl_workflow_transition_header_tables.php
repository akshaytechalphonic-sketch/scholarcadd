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
        Schema::create('tbl_workflow_transition_header', function (Blueprint $table) {
             $table->id();
            $table->string('ref_code', 191)->nullable();
            $table->unsignedBigInteger('workflow_map_id')->nullable();
            $table->unsignedBigInteger('workflow_steps_id')->nullable();
            $table->unsignedBigInteger('workflow_setup_id')->nullable();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->string('document_code', 191)->nullable();
            $table->unsignedBigInteger('requester_id')->nullable();
            $table->date('date')->nullable();
            $table->enum('status', ['Active', 'Completed'])->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
             $table->date('deleted_at')->nullable();
              $table->string('source')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_workflow_transition_header');
    }
};
