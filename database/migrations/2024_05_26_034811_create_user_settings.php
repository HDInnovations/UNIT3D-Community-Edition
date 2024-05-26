<?php

declare(strict_types=1);

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();
            $table->boolean('censor')->default('0');
            $table->boolean('chat_hidden')->default('0');
            $table->string('locale')->default('en');
            $table->unsignedTinyInteger('style')->default('0');
            $table->unsignedTinyInteger('torrent_layout')->default('0');
            $table->boolean('torrent_filters')->default('0');
            $table->string('custom_css')->nullable();
            $table->string('standalone_css')->nullable();
            $table->boolean('show_poster')->default('0');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('user_privacy', function (Blueprint $table): void {
            $table->boolean('private_profile')->default('0')->after('user_id');
            $table->boolean('hidden')->default('0')->after('private_profile');
        });

        Schema::table('user_notifications', function (Blueprint $table): void {
            $table->boolean('block_notifications')->default('0')->after('user_id');
        });

        DB::table('user_settings')->insertUsing(
            [
                'user_id',
                'censor',
                'chat_hidden',
                'locale',
                'style',
                'torrent_layout',
                'torrent_filters',
                'custom_css',
                'standalone_css',
                'show_poster',
                'created_at',
                'updated_at',
            ],
            DB::table('users')->select([
                'id',
                'censor',
                'chat_hidden',
                'locale',
                'style',
                'torrent_layout',
                'torrent_filters',
                'custom_css',
                'standalone_css',
                'show_poster',
                'created_at',
                'updated_at',
            ])
        );

        DB::table('users')
            ->join('user_privacy', 'user_privacy.user_id', '=', 'users.id')
            ->update([
                'user_privacy.private_profile' => DB::raw('users.private_profile'),
                'user_privacy.hidden'          => DB::raw('users.hidden'),
            ]);

        DB::table('users')
            ->join('user_notifications', 'user_notifications.user_id', '=', 'users.id')
            ->update([
                'user_notifications.block_notifications' => DB::raw('users.block_notifications'),
            ]);

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'censor',
                'chat_hidden',
                'hidden',
                'locale',
                'style',
                'torrent_layout',
                'torrent_filters',
                'custom_css',
                'standalone_css',
                'show_poster',
                'private_profile',
                'block_notifications',
            ]);
        });
    }
};
