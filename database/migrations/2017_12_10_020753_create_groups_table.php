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
        Schema::create('groups', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('slug');
            $table->integer('position');
            $table->string('color');
            $table->string('icon');
            $table->string('effect')->default('none');
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_modo')->default(0);
            $table->boolean('is_trusted')->default(0);
            $table->boolean('is_immune')->default(0);
            $table->boolean('is_freeleech')->default(0);
            $table->boolean('autogroup')->default(0);
        });
    }
};
