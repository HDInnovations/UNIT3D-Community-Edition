<?php

use App\Models\Role;
use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('position');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('effect')->default('none');
            $table->integer('rule_id')->nullable();
            $table->boolean('system_required')->default(false);
            $table->timestamps();
        });

        foreach (Group::all() as $group) {
            $role = new Role();
            $role->position = $group->position;
            $role->name = $group->name;
            $role->slug = $group->slug;
            $role->description = $group->description;
            $role->color = $group->color;
            $role->position = $group->position;
            $role->icon = $group->icon;
            $role->effect = $group->effect;
            $role->save();
        }

        Schema::dropIfExists('groups');
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
