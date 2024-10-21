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
 * @author     Roardom <roardom@protonmail.com>
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
        Schema::create('events', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('icon');
            $table->boolean('active');
            $table->date('starts_at');
            $table->date('ends_at');
            $table->timestamps();
        });

        Schema::create('prizes', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->string('type');
            $table->unsignedInteger('min');
            $table->unsignedInteger('max');
            $table->unsignedInteger('weight');

            $table->foreign('event_id')->references('id')->on('events');

            $table->timestamps();
        });

        Schema::create('claimed_prizes', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('event_id');
            $table->unsignedBigInteger('bon');
            $table->unsignedInteger('fl_tokens');

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }
};
