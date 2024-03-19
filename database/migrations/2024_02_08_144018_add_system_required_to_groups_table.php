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
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('system_required')->default(false);
        });

        DB::table('groups')
            ->where('slug', '=', 'pruned')
            ->update([
                'system_required' => true,
            ]);

        DB::table('groups')
            ->where('slug', '=', 'banned')
            ->update([
                'system_required' => true,
            ]);

        DB::table('groups')
            ->where('slug', '=', 'disabled')
            ->update([
                'system_required' => true,
            ]);

        DB::table('groups')
            ->where('slug', '=', 'validating')
            ->update([
                'system_required' => true,
            ]);

        DB::table('groups')
            ->where('slug', '=', 'leech')
            ->update([
                'system_required' => true,
            ]);

        DB::table('groups')
            ->where('slug', '=', 'user')
            ->update([
                'system_required' => true,
            ]);
    }
};
