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

class CreateBonTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bon_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('itemID')->default(0);
            $table->string('name')->default('');
            $table->float('cost', 22, 2)->default(0.00);
            $table->unsignedBigInteger('sender')->default(0);
            $table->unsignedBigInteger('receiver')->default(0);
            $table->unsignedBigInteger('torrent_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->text('comment');
            $table->timestamp('date_actioned')->useCurrent();
        });

        Schema::table('bon_transactions', function (Blueprint $table) {
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bon_transactions');
    }
}
