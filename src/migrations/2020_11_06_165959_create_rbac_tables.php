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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias')->unique()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('rbac_permission_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('rbac_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias')->unique()->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('rbac_permission_groups');
        });

        Schema::create('rbac_permission_role', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('rbac_permissions');
            $table->foreign('role_id')->references('id')->on('rbac_roles');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('rbac_role_user', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('rbac_roles');
            try {
                $table->foreign('user_id')->references('id')->on('users');
            } catch (Exception $ex) {
                $table->index('user_id');
            }

            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rbac_role_user');
        Schema::dropIfExists('rbac_permission_role');
        Schema::dropIfExists('rbac_permissions');
        Schema::dropIfExists('rbac_permission_groups');
        Schema::dropIfExists('rbac_roles');
    }
}
