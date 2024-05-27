<?php

declare(strict_types=1);

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
        Schema::create('person', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('imdb_id')->nullable();
            $table->string('known_for_department')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('popularity')->nullable();
            $table->string('profile')->nullable();
            $table->string('still')->nullable();
            $table->string('adult')->nullable();
            $table->mediumText('also_known_as')->nullable();
            $table->mediumText('biography')->nullable();
            $table->string('birthday')->nullable();
            $table->string('deathday')->nullable();
            $table->string('gender')->nullable();
            $table->string('homepage')->nullable();
        });
    }
};
