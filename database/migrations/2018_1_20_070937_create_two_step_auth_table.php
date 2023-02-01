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

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('twostep_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')->unsigned()->index();
            $table->string('authCode')->nullable();
            $table->integer('authCount');
            $table->boolean('authStatus')->default(false);
            $table->dateTime('authDate')->nullable();
            $table->dateTime('requestDate')->nullable();
            $table->timestamps();
        });
    }
};
