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
        Schema::create('tbl_workflow_event_history', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('type', 191)->nullable();
            $table->unsignedBigInteger('doc_id');
            $table->string('statement', 191)->nullable();
            $table->string('status', 191)->nullable();
            $table->date('event_date')->nullable();
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
        Schema::dropIfExists('tbl_workflow_event_history');
    }
};
