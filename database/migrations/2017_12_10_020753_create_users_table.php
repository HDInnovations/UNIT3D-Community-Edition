<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('passkey');
            $table->integer('group_id')->index('fk_users_groups_idx');
            $table->boolean('active')->default(0);
            $table->bigInteger('uploaded')->unsigned()->default(0);
            $table->bigInteger('downloaded')->unsigned()->default(0);
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('about', 500)->nullable();
            $table->text('signature', 16777215)->nullable();
            $table->integer('fl_tokens')->unsigned()->default(0);
            $table->float('seedbonus', 12)->unsigned()->default(0.00);
            $table->integer('invites')->unsigned()->default(0);
            $table->integer('hitandruns')->unsigned()->default(0);
            $table->string('rsskey');
            $table->boolean('hidden')->default(0);
            $table->boolean('style')->default(0);
            $table->boolean('nav')->default(0);
            $table->boolean('ratings')->default(0);
            $table->boolean('can_chat')->default(1);
            $table->boolean('can_comment')->default(1);
            $table->boolean('can_download')->default(1);
            $table->boolean('can_request')->default(1);
            $table->boolean('can_invite')->default(1);
            $table->boolean('can_upload')->default(1);
            $table->boolean('show_poster')->default(0);
            $table->boolean('peer_hidden')->default(0);
            $table->boolean('private_profile')->default(0);
            $table->boolean('stat_hidden')->default(0);
            $table->string('remember_token')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
