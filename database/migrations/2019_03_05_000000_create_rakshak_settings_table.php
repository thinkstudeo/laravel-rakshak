<?php

use Illuminate\Support\Facades\Schema;
use Thinkstudeo\Rakshak\RakshakSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRakshakSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rakshak_settings', function (Blueprint $table) {
            $table->uuid('id');
            $table->boolean('enable_2fa')->default(false);
            $table->string('channel_2fa')->default('email');
            $table->string('control_level_2fa')->default('admin');
            $table->timestamps();

            $table->primary('id');
        });

        RakshakSetting::create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rakshak_settings');
    }
}
