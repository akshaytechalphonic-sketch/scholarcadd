<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('email');
            $table->enum('group_size', [
                '1-10',
                '11-20',
                '21-30',
                '31-60',
                '61-100',
                'nolimit',
            ])->nullable();
        });
        Schema::table('tbl_learning_calenders', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('email');
            $table->enum('group_size', [
                '1-10',
                '11-20',
                '21-30',
                '31-60',
                '61-100',
                'nolimit',
            ])->nullable();
        });
        Schema::table('tbl_consultation_enquiries', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('email');
            $table->enum('group_size', [
                '1-10',
                '11-20',
                '21-30',
                '31-60',
                '61-100',
                'nolimit',
            ])->nullable();
        });
        Schema::table('tbl_schedule_appointments', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('email');
            $table->enum('group_size', [
                '1-10',
                '11-20',
                '21-30',
                '31-60',
                '61-100',
                'nolimit',
            ])->nullable();
        });
        Schema::table('tbl_team_applications', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('email');
            $table->enum('group_size', [
                '1-10',
                '11-20',
                '21-30',
                '31-60',
                '61-100',
                'nolimit',
            ])->nullable();
        });
        Schema::table('tbl_request_for_services', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('email');
            $table->enum('group_size', [
                '1-10',
                '11-20',
                '21-30',
                '31-60',
                '61-100',
                'nolimit',
            ])->nullable();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'group_size']);
        });
 
        Schema::table('tbl_learning_calenders', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'group_size']);
        });
 
        Schema::table('tbl_consultation_enquiries', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'group_size']);
        });
 
        Schema::table('tbl_schedule_appointments', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'group_size']);
        });
 
        Schema::table('tbl_team_applications', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'group_size']);
        });
 
        Schema::table('tbl_request_for_services', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'group_size']);
        });
    }
 
};