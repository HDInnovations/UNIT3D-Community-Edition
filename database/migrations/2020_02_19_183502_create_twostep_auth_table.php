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

class CreateTwostepAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twostep_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->string('authCode')->nullable();
            $table->integer('authCount');
            $table->boolean('authStatus')->default(0);
            $table->dateTime('authDate')->nullable();
            $table->dateTime('requestDate')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('twostep_auth', function (Blueprint $table) {
            $table->index('userId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twostep_auth');
    }
}