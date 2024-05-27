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
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('private_messages', function (Blueprint $table): void {
            $table->dropForeign(['sender_id']);
            $table->dropIndex(['sender_id', 'read']);
            $table->index(['receiver_id', 'read']);
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->index(['notifiable_type', 'notifiable_id', 'read_at']);
        });

        Schema::table('warnings', function (Blueprint $table): void {
            $table->index(['user_id', 'active', 'deleted_at']);
        });

        Schema::table('genre_movie', function (Blueprint $table): void {
            $table->index('movie_id');
        });
    }
};
