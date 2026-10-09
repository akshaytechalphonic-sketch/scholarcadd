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
        Schema::create('tbl_workflow_map_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('map_id')->nullable();
            $table->unsignedBigInteger('workflow_setup_id')->nullable();
            $table->date('mapping_date')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->integer('version')->nullable();
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
        Schema::dropIfExists('tbl_workflow_map_log');
    }
};
