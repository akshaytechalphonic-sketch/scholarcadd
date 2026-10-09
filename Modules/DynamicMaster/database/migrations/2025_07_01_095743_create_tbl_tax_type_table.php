<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_tax_type', function (Blueprint $table) {
            $table->id();
            $table->string('tax_type_name', 100);
            $table->string('tax_type_code', 20)->unique();
            $table->string('tax_category', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_tax_type');
    }
};
