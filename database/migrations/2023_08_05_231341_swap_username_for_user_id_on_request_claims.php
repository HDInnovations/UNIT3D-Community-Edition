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
    public function up(): void
    {
        Schema::table('request_claims', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable()->after('request_id');
        });

        DB::table('request_claims')
            ->leftJoin('users', 'request_claims.username', '=', 'users.username')
            ->update([
                'user_id' => DB::raw('users.id'),
            ]);

        DB::table('request_claims')
            ->whereNull('user_id')
            ->delete();

        Schema::table('request_claims', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->dropColumn('username');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
