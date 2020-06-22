<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable();
            $table->string('mobile')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->boolean('enable_2fa')->default(false);
            $table->unsignedInteger('otp_token')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->string('otp_channel')->default('email');
            $table->string('status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile_verified_at');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('enable_2fa');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_token');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_expiry');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_channel');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
