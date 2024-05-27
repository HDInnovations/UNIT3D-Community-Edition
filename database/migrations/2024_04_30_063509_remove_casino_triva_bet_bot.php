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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bot_transactions', function ($table): void {
            $table->drop();
        });

        // Delete Casino Bot, Bet Bot and Trivia Bot respectively
        DB::table('bots')->whereIn('id', [3,4,5])->delete();

        Schema::table('bots', function ($table): void {
            $table->dropColumn([
                'is_triviabot',
                'is_casinobot',
                'is_betbot',
            ]);
        });
    }
};
