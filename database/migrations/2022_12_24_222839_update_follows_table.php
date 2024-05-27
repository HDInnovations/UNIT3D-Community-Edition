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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete duplicates
        DB::table('follows')
            ->whereNotIn('id', DB::query()->fromSub(function ($query): void {
                $query->from('follows')->selectRaw('MIN(id)')->groupBy('user_id', 'target_id');
            }, 'f'))
            ->delete();

        Schema::table('follows', function (Blueprint $table): void {
            $table->dropColumn('id');
            $table->primary(['user_id', 'target_id']);
        });
    }
};
