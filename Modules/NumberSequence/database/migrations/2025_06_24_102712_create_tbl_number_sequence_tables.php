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
        Schema::create('tbl_number_sequence', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('code', 60);
            $table->string('constant_no', 10);
            $table->string('range_no', 2)->nullable();
            $table->date('maping_date')->nullable();
            $table->integer('next')->default(1);
            $table->string('format', 30);
            $table->enum('status', ['Active', 'Inactive']);
            $table->unsignedBigInteger('numbersequence_map_id')->nullable();
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
        Schema::dropIfExists('tbl_number_sequence');
    }
};
