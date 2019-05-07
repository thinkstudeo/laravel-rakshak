<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Thinkstudeo\Rakshak\Role;

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

        Role::create(['name' => config('rakshak.roles.super_user'), 'label' => 'Super User', 'description' => 'All abilities for the application interaction.']);
        Role::create(['name' => config('rakshak.roles.authorizer'), 'label' => 'Roles & Abilities Manager', 'description' => 'Can perform crud ops for Role and Ability']);
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