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
 * @author     Roardom <roardom@protonmail.com>
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
        Schema::create('torrent_moderation_messages', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('moderated_by')->index();
            $table->unsignedInteger('torrent_id')->index();
            $table->smallInteger('status')->default(0);
            $table->text('message')->nullable();
            $table->timestamps();
        });

        // Migrate "moderated_by" to new table using chunking
        DB::table('torrents')->where('moderated_by', '!=', null)->orderBy('id')->chunk(10000, function ($torrents): void {
            $insertData = [];

            foreach ($torrents as $torrent) {
                // Convert to array to prevent PHPStan errors
                $torrentArray = (array) $torrent;

                $insertData[] = [
                    'moderated_by' => $torrentArray['moderated_by'],
                    'torrent_id'   => $torrentArray['id'],
                    'status'       => $torrentArray['status'],
                    'message'      => null,
                    'created_at'   => $torrentArray['moderated_at'],
                    'updated_at'   => $torrentArray['moderated_at'],
                ];
            }

            // Only insert if there's data to insert
            if (!empty($insertData)) {
                DB::table('torrent_moderation_messages')->insert($insertData);
            }
        });

        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropColumn('moderated_at');
            $table->dropColumn('moderated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->unsignedInteger('moderated_by')->nullable()->index()->after('status');
            $table->dateTime('moderated_at')->nullable()->after('status');
        });

        $torrentModerationMessages = DB::table('torrent_moderation_messages')->get();

        foreach ($torrentModerationMessages as $message) {
            DB::table('torrents')->where('id', $message->torrent_id)->update([
                'moderated_by' => $message->moderated_by,
                'moderated_at' => $message->created_at,
            ]);
        }

        Schema::dropIfExists('torrent_moderation_messages');
    }
};
