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

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bon_transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('itemID')->unsigned()->default(0);
            $table->string('name')->default('');
            $table->float('cost', 22)->default(0.00);
            $table->integer('sender')->unsigned()->default(0);
            $table->integer('receiver')->unsigned()->default(0);
            $table->integer('torrent_id')->nullable();
            $table->text('comment', 65535);
            $table->timestamp('date_actioned')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }
};
