<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rbac_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias')->unique()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('rbac_permission_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('rbac_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('group_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('rbac_permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('rbac_permissions');
            $table->foreign('role_id')->references('id')->on('rbac_roles');
        });

        Schema::create('rbac_role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('rbac_roles');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('rbac_role_user');
        Schema::drop('rbac_permission_role');
        Schema::drop('rbac_permissions');
        Schema::drop('rbac_roles');
    }
}
