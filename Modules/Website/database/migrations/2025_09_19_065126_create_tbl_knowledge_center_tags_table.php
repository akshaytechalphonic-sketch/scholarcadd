<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_knowledge_center_tags', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('knowledge_center_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('knowledge_center_id')
                ->references('id')->on('tbl_knowledge_center')
                ->onDelete('cascade');

            $table->foreign('tag_id')
                ->references('id')->on('tbl_tags')
                ->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')->on('tbl_company')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_knowledge_center_tags');
    }
};
