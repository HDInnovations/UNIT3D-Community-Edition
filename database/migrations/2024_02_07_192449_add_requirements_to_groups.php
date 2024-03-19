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
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->unsignedBigInteger('min_uploaded')->nullable();
            $table->unsignedBigInteger('min_seedsize')->nullable();
            $table->unsignedBigInteger('min_avg_seedtime')->nullable();
            $table->decimal('min_ratio', 4, 2)->nullable();
            $table->unsignedBigInteger('min_age')->nullable();
        });

        // Leech
        DB::table('groups')
            ->where('id', '=', 15)
            ->update([
                'min_uploaded'     => 0,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0,
                'min_age'          => 0,
            ]);

        // User
        DB::table('groups')
            ->where('id', '=', 3)
            ->update([
                'min_uploaded'     => 0,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0.4,
                'min_age'          => 0,
            ]);

        // Power User
        DB::table('groups')
            ->where('id', '=', 11)
            ->update([
                'min_uploaded'     => 1024 * 1024 * 1024 * 1024,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0.4,
                'min_age'          => 30 * 24 * 3600,
            ]);

        // Super User
        DB::table('groups')
            ->where('id', '=', 12)
            ->update([
                'min_uploaded'     => 5 * 1024 * 1024 * 1024 * 1024,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0.4,
                'min_age'          => 60 * 24 * 3600,
            ]);

        // Extreme User
        DB::table('groups')
            ->where('id', '=', 13)
            ->update([
                'min_uploaded'     => 20 * 1024 * 1024 * 1024 * 1024,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0.4,
                'min_age'          => 90 * 24 * 3600,
            ]);

        // Insane User
        DB::table('groups')
            ->where('id', '=', 14)
            ->update([
                'min_uploaded'     => 50 * 1024 * 1024 * 1024 * 1024,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0.4,
                'min_age'          => 180 * 24 * 3600,
            ]);

        // Seeder
        DB::table('groups')
            ->where('id', '=', 17)
            ->update([
                'min_uploaded'     => 0,
                'min_seedsize'     => 5 * 1024 * 1024 * 1024 * 1024,
                'min_avg_seedtime' => 30 * 24 * 3600,
                'min_ratio'        => 0.4,
                'min_age'          => 30 * 24 * 3600,
            ]);

        // Veteran
        DB::table('groups')
            ->where('id', '=', 16)
            ->update([
                'min_uploaded'     => 100 * 1024 * 1024 * 1024 * 1024,
                'min_seedsize'     => 0,
                'min_avg_seedtime' => 0,
                'min_ratio'        => 0.4,
                'min_age'          => 365 * 24 * 3600,
            ]);

        // Archivist
        DB::table('groups')
            ->where('id', '=', 18)
            ->update([
                'min_uploaded'     => 0,
                'min_seedsize'     => 10 * 1024 * 1024 * 1024 * 1024,
                'min_avg_seedtime' => 60 * 24 * 3600,
                'min_ratio'        => 0.4,
                'min_age'          => 90 * 24 * 3600,
            ]);
    }
};
