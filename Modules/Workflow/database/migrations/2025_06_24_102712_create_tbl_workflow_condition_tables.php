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
        Schema::create('tbl_workflow_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_setup_id');
            $table->unsignedBigInteger('workflow_step_id');
            $table->string('field', 100);
            $table->enum('condition', [
                'Equals to',
                'Less Than',
                'Less Than Equals to',
                'Greater Than',
                'Greater Than Equals to',
                'In Between'
            ]);
            $table->string('condition_value', 100);
            $table->unsignedBigInteger('company_id');
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
        Schema::dropIfExists('tbl_workflow_condition');
    }
};
