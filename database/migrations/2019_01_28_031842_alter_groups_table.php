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

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->integer('level')->default(0)->after('position');
        });
        DB::table('groups')->whereRaw('id = 10')->update(['level' => 9999]);
        DB::table('groups')->whereRaw(' id=4')->update(['level' => 5000]);
        DB::table('groups')->whereRaw(' id=6')->update(['level' => 2500]);
        DB::table('groups')->whereRaw(' id=8')->update(['level' => 1000]);
        DB::table('groups')->whereRaw(' id=19')->update(['level' => 500]);
        DB::table('groups')->whereRaw(' id=7')->update(['level' => 250]);
        DB::table('groups')->whereRaw(' id=16')->update(['level' => 100]);
        DB::table('groups')->whereRaw(' id=18')->update(['level' => 90]);
        DB::table('groups')->whereRaw(' id=17')->update(['level' => 80]);
        DB::table('groups')->whereRaw(' id=14')->update(['level' => 70]);
        DB::table('groups')->whereRaw(' id=13')->update(['level' => 60]);
        DB::table('groups')->whereRaw(' id=12')->update(['level' => 50]);
        DB::table('groups')->whereRaw(' id=11')->update(['level' => 40]);
        DB::table('groups')->whereRaw(' id=3')->update(['level' => 30]);
        DB::table('groups')->whereRaw(' id=15')->update(['level' => 20]);
        DB::table('groups')->whereRaw(' id=2')->update(['level' => 10]);
    }
};
