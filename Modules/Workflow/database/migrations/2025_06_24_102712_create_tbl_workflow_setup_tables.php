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
        Schema::create('tbl_workflow_setup', function (Blueprint $table) {
             $table->id();
            $table->string('workflow_code', 191);
            $table->string('workflow_name', 191);
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->unsignedBigInteger('apply_on_id');
            $table->date('mapping_date')->nullable();
            $table->unsignedBigInteger('workflow_map_id')->nullable();
            $table->integer('version')->default(0);
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
        Schema::dropIfExists('tbl_workflow_setup');
    }
};
