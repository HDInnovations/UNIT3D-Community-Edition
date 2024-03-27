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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->unsignedInteger('sender')->nullable()->default(null)->change();
            $table->unsignedInteger('receiver')->nullable()->default(null)->change();
            $table->unsignedInteger('torrent_id')->nullable()->default(null)->change();
        });

        DB::table('bon_transactions')->where('sender', '=', 0)->update([
            'sender' => null,
        ]);

        DB::table('bon_transactions')->where('receiver', '=', 0)->update([
            'receiver' => null,
        ]);

        DB::table('bon_transactions')->where('torrent_id', '=', 0)->update([
            'torrent_id' => null,
        ]);
    }
};
