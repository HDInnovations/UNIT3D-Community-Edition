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
        DB::table('peers')->truncate();

        Schema::disableForeignKeyConstraints();

        Schema::table('peers', function (Blueprint $table): void {
            $table->dropColumn(['md5_peer_id', 'info_hash']);
            $table->unsignedSmallInteger('port')->nullable(false)->change();
            $table->string('agent', 64)->nullable(false)->change();
            $table->unsignedBigInteger('uploaded')->nullable(false)->change();
            $table->unsignedBigInteger('downloaded')->nullable(false)->change();
            $table->unsignedBigInteger('left')->nullable(false)->change();
            $table->boolean('seeder')->nullable(false)->change();
            $table->unsignedInteger('torrent_id')->nullable(false)->change();
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->binary('peer_id', length: 20, fixed: true)->change();
            $table->binary('ip', length: 16)->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};
