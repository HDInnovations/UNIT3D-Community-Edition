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

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position');
            $table->string('slug');
            $table->string('name');
            $table->string('command');
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('emoji')->nullable();
            $table->string('info')->nullable();
            $table->string('about', 500)->nullable();
            $table->text('help')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('is_protected')->default(0);
            $table->boolean('is_triviabot')->default(0);
            $table->boolean('is_nerdbot')->default(0);
            $table->boolean('is_systembot')->default(0);
            $table->boolean('is_casinobot')->default(0);
            $table->boolean('is_betbot')->default(0);
            $table->unsignedBigInteger('uploaded')->default(0);
            $table->unsignedBigInteger('downloaded')->default(0);
            $table->unsignedInteger('fl_tokens')->default(0);
            $table->float('seedbonus', 12, 2)->default(0.00);
            $table->unsignedInteger('invites')->default(0);
            $table->nullableTimestamps();
        });

        Schema::table('bots', function (Blueprint $table) {
            $table->index('is_betbot');
            $table->index('active');
            $table->index('is_triviabot');
            $table->index('is_systembot');
            $table->index('is_casinobot');
            $table->index('is_protected');
            $table->index('is_nerdbot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bots');
    }
}