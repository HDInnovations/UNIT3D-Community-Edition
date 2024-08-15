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
        Schema::create('announces', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('torrent_id')->index();
            $table->unsignedBigInteger('uploaded');
            $table->unsignedBigInteger('downloaded');
            $table->unsignedBigInteger('left');
            $table->unsignedBigInteger('corrupt');
            $table->binary('peer_id', length: 20, fixed: true);
            $table->unsignedSmallInteger('port');
            $table->unsignedSmallInteger('numwant');
            $table->timestamp('created_at')->useCurrent();
            $table->string('event');
            $table->string('key');

            $table->index(['user_id', 'torrent_id']);
        });
    }
};
