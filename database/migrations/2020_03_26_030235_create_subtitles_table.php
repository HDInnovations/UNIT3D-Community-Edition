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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('file_name');
            $table->bigInteger('file_size')->unsigned();
            $table->integer('language_id')->index();
            $table->string('extension');
            $table->text('note')->nullable();
            $table->integer('downloads')->nullable();
            $table->boolean('verified')->default(0)->index();
            $table->integer('user_id')->index();
            $table->integer('torrent_id')->index();
            $table->boolean('anon')->default(0);
            $table->smallInteger('status')->default(0);
            $table->dateTime('moderated_at')->nullable();
            $table->integer('moderated_by')->nullable()->index();
            $table->timestamps();
        });
    }
};
