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
            $table->dropColumn('slug');
        });

        Schema::table('bots', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('distributors', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('forums', function (Blueprint $table): void {
            $table->dropColumn('last_topic_slug');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('polls', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('regions', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('resolutions', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('topics', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });

        Schema::table('types', function (Blueprint $table): void {
            $table->dropColumn('slug');
        });
    }
};
