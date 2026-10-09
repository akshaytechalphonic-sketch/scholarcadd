<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_promo_code', function (Blueprint $table) {
            $table->id();

            $table->string('promocode', 50)->unique();
            $table->string('text')->nullable();
            $table->unsignedBigInteger('course_id')->nullable()->index();
            $table->decimal('discount_percentage', 10, 2);
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');

            // Timestamps (nullable like your style)
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            // Audit fields (like your state table)
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            // Foreign keys (nullable safe)
            $table->foreign('course_id')->references('id')->on('tbl_courses')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('tbl_company')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_promo_code');
    }
};
