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
        Schema::create('episodes', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->mediumText('overview')->nullable();
            $table->string('production_code')->nullable();
            $table->integer('season_number');
            $table->integer('season_id')->index();
            $table->string('still')->nullable();
            $table->integer('tv_id');
            $table->string('type')->nullable();
            $table->string('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->string('air_date')->nullable();
            $table->integer('episode_number')->nullable();
            $table->timestamps();
        });
    }
};
