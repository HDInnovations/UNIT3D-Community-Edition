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
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `peers` MODIFY `ipv6` VARBINARY(16) NOT NULL');

        // Migrate existing IPv6 addresses to new column
        DB::statement('UPDATE `peers` SET `ipv6` = `ip` WHERE LENGTH(`ip`) = 16');
        // Remove old IPv6 addresses from old column
        DB::statement('UPDATE `peers` SET `ip` = "" WHERE LENGTH(`ip`) = 16');
    }
};
