<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('passkey');
            $table->integer('group_id');
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('uploaded')->default(0);
            $table->unsignedBigInteger('downloaded')->default(0);
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('about', 500)->nullable();
            $table->text('signature')->nullable();
            $table->unsignedInteger('fl_tokens')->default(0);
            $table->float('seedbonus', 12, 2)->default(0.00);
            $table->unsignedInteger('invites')->default(0);
            $table->unsignedInteger('hitandruns')->default(0);
            $table->string('rsskey');
            $table->unsignedInteger('chatroom_id')->default(1);
            $table->boolean('censor')->default(0);
            $table->boolean('chat_hidden')->default(0);
            $table->boolean('hidden')->default(0);
            $table->boolean('style')->default(0);
            $table->boolean('nav')->default(0);
            $table->boolean('torrent_layout')->default(0);
            $table->boolean('torrent_filters')->default(0);
            $table->string('custom_css')->nullable();
            $table->boolean('ratings')->default(0);
            $table->boolean('read_rules')->default(0);
            $table->boolean('can_chat')->default(1);
            $table->boolean('can_comment')->default(1);
            $table->boolean('can_download')->default(1);
            $table->boolean('can_request')->default(1);
            $table->boolean('can_invite')->default(1);
            $table->boolean('can_upload')->default(1);
            $table->boolean('show_poster')->default(0);
            $table->boolean('peer_hidden')->default(0);
            $table->boolean('private_profile')->default(0);
            $table->boolean('block_notifications')->default(0);
            $table->boolean('stat_hidden')->default(0);
            $table->boolean('twostep')->default(0);
            $table->rememberToken();
            $table->string('api_token', 100)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_action')->nullable();
            $table->dateTime('disabled_at')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->nullableTimestamps();
            $table->string('locale')->default('en');
            $table->unsignedInteger('chat_status_id')->default(1);
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('group_id', 'fk_users_groups_idx');
            $table->index('block_notifications');
            $table->unique('api_token', 'users_api_token_unique');
            $table->index('torrent_filters');
            $table->index('read_rules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

}