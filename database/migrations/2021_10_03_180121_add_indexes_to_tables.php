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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            if (!Schema::hasIndex('articles', 'created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('bon_transactions', function (Blueprint $table): void {
            if (!Schema::hasIndex('bon_transactions', 'itemID')) {
                $table->index('itemID');
            }

            if (!Schema::hasIndex('bon_transactions', 'sender')) {
                $table->index('sender');
            }

            if (!Schema::hasIndex('bon_transactions', 'receiver')) {
                $table->index('receiver');
            }

            if (!Schema::hasIndex('bon_transactions', 'torrent_id')) {
                $table->index('torrent_id');
            }
        });

        Schema::table('bookmarks', function (Blueprint $table): void {
            if (!Schema::hasIndex('bookmarks', 'user_id')) {
                $table->index('user_id');
            }

            if (!Schema::hasIndex('bookmarks', 'torrent_id')) {
                $table->index('torrent_id');
            }
        });

        Schema::table('posts', function (Blueprint $table): void {
            if (!Schema::hasIndex('posts', 'created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('topics', function (Blueprint $table): void {
            if (!Schema::hasIndex('topics', 'created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('torrents', function (Blueprint $table): void {
            if (!Schema::hasIndex('torrents', 'status')) {
                $table->index('status');
            }

            if (!Schema::hasIndex('torrents', 'seeders')) {
                $table->index('seeders');
            }

            if (!Schema::hasIndex('torrents', 'leechers')) {
                $table->index('leechers');
            }

            if (!Schema::hasIndex('torrents', 'sticky')) {
                $table->index('sticky');
            }

            if (!Schema::hasIndex('torrents', 'created__at')) {
                $table->index('created_at');
            }

            if (!Schema::hasIndex('torrents', 'bumped_at')) {
                $table->index('bumped_at');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            if (!Schema::hasIndex('users', 'deleted_at')) {
                $table->index('deleted_at');
            }
        });
    }
};
