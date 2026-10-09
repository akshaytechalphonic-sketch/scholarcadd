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
        Schema::create('tbl_workflow_step', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_setup_id');
            $table->string('step_name', 191)->nullable();
            $table->enum('condition', [
                'Employee',
                'Line Department',
                'Other Department',
                'Line Manager',
                'Signatory',
                'Conditional',
                'Tender Committee Member'
            ]);
            $table->enum('substep_type', ['True', 'False'])->nullable();
            $table->unsignedBigInteger('next_step_id')->nullable();
            $table->integer('no_of_approver')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->enum('system_notification', ['Yes', 'No'])->default('Yes');
            $table->enum('email_notification', ['Yes', 'No'])->nullable();
            $table->unsignedBigInteger('email_template_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
             $table->date('deleted_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_workflow_step');
    }
};
