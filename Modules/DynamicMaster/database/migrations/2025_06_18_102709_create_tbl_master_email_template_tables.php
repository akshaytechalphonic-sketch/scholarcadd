<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_master_email_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 60);
            $table->string('code', 60);
            $table->string('subject', 200)->nullable();
            $table->string('veriable', 191)->nullable();
            $table->text('content')->nullable();
            $table->unsignedBigInteger('email_master_id')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_master_email_template');
    }
};
