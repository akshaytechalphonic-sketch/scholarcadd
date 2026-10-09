<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomColumnsToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('emp_id')->nullable()->after('id');
            $table->enum('type', [ 'User', 'Employee'])->nullable()->after('emp_id');
            $table->enum('status', ['Active', 'Blocked'])->default('Active')->after('type');
            $table->longText('profile_img')->nullable()->after('status');
            $table->string('reset_password_token', 250)->nullable()->after('profile_img');
            $table->string('reset_password_expiry_time', 50)->nullable()->after('reset_password_token');
            $table->boolean('two_factor_enabled')->nullable()->after('reset_password_expiry_time');
            $table->string('google2fa_secret', 20)->nullable()->after('two_factor_enabled');
            $table->text('blocked_reason')->nullable()->after('google2fa_secret');
            $table->string('blocked_type', 191)->nullable()->after('blocked_reason');
            $table->timestamp('blocked_at')->nullable()->after('blocked_type');
            $table->timestamp('password_expired')->nullable()->after('blocked_at');
            $table->string('session_id', 191)->nullable()->after('password_expired');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'emp_id',
                'type',
                'status',
                'profile_img',
                'reset_password_token',
                'reset_password_expiry_time',
                'two_factor_enabled',
                'google2fa_secret',
                'blocked_reason',
                'blocked_type',
                'blocked_at',
                'password_expired',
                'session_id',
            ]);
        });
    }
}
