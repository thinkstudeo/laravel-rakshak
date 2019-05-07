<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->unique();
            $table->string('label');
            $table->string('slug')->unique();
            $table->string('description');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->primary('id');
        });

        Role::create(['name' => 'super', 'label' => 'Super User', 'description' => 'All abilities for the application interaction.']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}