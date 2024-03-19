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
        //Create Table
        Schema::create('internals', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('icon')->default('none');
            $table->string('effect')->default('none');
        });

        //Update Users Table
        Schema::table('users', function (Blueprint $table): void {
            $table->integer('internal_id')->index('fk_users_internal_idx')->after('group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Delete Table
        Schema::dropIfExists('internals');

        //Update Users Table
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('internal_id');
        });
    }
};
