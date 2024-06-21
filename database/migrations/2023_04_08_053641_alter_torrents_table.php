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
        Schema::table('torrents', function (Blueprint $table): void {
            $table->binary('info_hash2', length: 20, fixed: true);
        });

        DB::table('torrents')->update([
            'info_hash2' => DB::raw('UNHEX(info_hash)'),
            'updated_at' => DB::raw('updated_at'),
        ]);

        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropColumn('info_hash');
            $table->renameColumn('info_hash2', 'info_hash');
            $table->index('info_hash');
        });
    }
};
