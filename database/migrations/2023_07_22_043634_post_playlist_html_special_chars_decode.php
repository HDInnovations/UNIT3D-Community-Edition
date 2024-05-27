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

return new class () extends Migration {
    public function up(): void
    {
        DB::table('posts')
            ->lazyById()
            ->each(function (object $post): void {
                DB::table('posts')
                    ->where('id', '=', $post->id)
                    ->update([
                        'content' => htmlspecialchars_decode($post->content),
                    ]);
            });

        DB::table('playlists')
            ->lazyById()
            ->each(function (object $playlist): void {
                DB::table('playlists')
                    ->where('id', '=', $playlist->id)
                    ->update([
                        'description' => htmlspecialchars_decode($playlist->description),
                    ]);
            });
    }
};
