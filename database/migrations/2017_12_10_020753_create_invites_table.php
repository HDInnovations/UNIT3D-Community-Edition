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
        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('user_id');
            $table->string('email');
            $table->string('code');
            $table->dateTime('expires_on')->nullable();
            $table->integer('accepted_by')->nullable()->index('accepted_by');
            $table->dateTime('accepted_at')->nullable();
            $table->text('custom')->nullable();
            $table->timestamps();
        });
    }
};
