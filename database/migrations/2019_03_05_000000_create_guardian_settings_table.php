<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Thinkstudeo\Guardian\GuardianSetting;

class CreateGuardianSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardian_settings', function (Blueprint $table) {
            $table->uuid('id');
            $table->boolean('enable_2fa')->default(false);
            $table->string('channel_2fa')->default('email');
            $table->string('control_level_2fa')->default('admin');
            $table->timestamps();

            $table->primary('id');
        });

        GuardianSetting::create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guardian_settings');
    }
}