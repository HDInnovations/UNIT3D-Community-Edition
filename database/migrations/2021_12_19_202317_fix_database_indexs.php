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
        // New Indexs
        DB::statement('ALTER TABLE `history` ADD INDEX `history_idx_prewa_hitru_immun_activ_actua` (`prewarn`,`hitrun`,`immune`,`active`,`actual_downloaded`)');
        DB::statement('ALTER TABLE `torrents` ADD INDEX `torrents_idx_status_resolut_created` (`status`,`resolution_id`,`created_at`)');
        DB::statement('ALTER TABLE `torrents` ADD INDEX `torrents_idx_status_catego_sticky_bumped` (`status`,`category_id`,`sticky`,`bumped_at`)');
        DB::statement('ALTER TABLE `torrents` ADD INDEX `torrents_idx_sticky_bumped_at` (`sticky`,`bumped_at`)');
        DB::statement('ALTER TABLE `peers` ADD INDEX `peers_idx_seeder_user_id` (`seeder`,`user_id`)');
        DB::statement('ALTER TABLE `torrents` ADD INDEX `torrents_idx_status_info_hash` (`status`,`info_hash`)');
    }
};
