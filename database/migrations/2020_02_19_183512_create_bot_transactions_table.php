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

class CreateBotTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable()->default('');
            $table->float('cost', 22, 2)->default(0.00);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('bot_id')->default(0);
            $table->boolean('to_user')->default(0);
            $table->boolean('to_bot')->default(0);
            $table->text('comment');
            $table->nullableTimestamps();
        });

        Schema::table('bot_transactions', function (Blueprint $table) {
            $table->index('type');
            $table->index('bot_id');
            $table->index('to_bot');
            $table->index('user_id');
            $table->index('to_user');

            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_transactions');
    }

}