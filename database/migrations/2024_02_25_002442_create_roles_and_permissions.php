<?php

use Database\Seeders\GroupRoleTableSeeder;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\PermissionsTableSeeder;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('roles', static function (Blueprint $table): void {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('position');
            $table->string('name');
            $table->string('description');
            $table->boolean('system_required');
            $table->boolean('auto_manage');
            $table->unsignedBigInteger('warnings_active_min')->nullable();
            $table->unsignedBigInteger('warnings_active_max')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', static function (Blueprint $table): void {
            $table->smallIncrements('id');
        });

        Schema::create('role_user', static function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('role_id');
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('auto_role_user', static function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('role_id');
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('group_role', static function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('group_id');
            $table->unsignedSmallInteger('role_id');
            $table->timestamps();

            $table->unique(['group_id', 'role_id']);

            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('permission_role', static function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedSmallInteger('permission_id');
            $table->unsignedSmallInteger('role_id');
            $table->boolean('authorized');
            $table->timestamps();

            $table->unique(['permission_id', 'role_id']);

            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnUpdate()->cascadeOnDelete();
        });

        (new PermissionsTableSeeder())->run();
        (new RolesTableSeeder())->run();
        (new PermissionRoleTableSeeder())->run();
        (new GroupRoleTableSeeder())->run();

        // TODO: Migrate existing perms to user_roles
        //        Schema::table('users', static function (Blueprint $table): void {
        //            $table->dropColumn([
        //                'can_chat',
        //                'can_comment',
        //                'can_download',
        //                'can_request',
        //                'can_invite',
        //                'can_upload',
        //            ]);
        //        });
        //        Schema::table('groups', static function (Blueprint $table): void {
        //            $table->dropColumn([
        //                'can_upload',
        //            ]);
        //        });
    }

    public function down(): void
    {
        Schema::drop('role_user');
        Schema::drop('auto_role_user');
        Schema::drop('group_role');
        Schema::drop('permission_role');
        Schema::drop('roles');
        Schema::drop('permissions');
    }
};
