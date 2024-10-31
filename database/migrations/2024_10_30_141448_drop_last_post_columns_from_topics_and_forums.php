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
        Schema::table('topics', function (Blueprint $table): void {
            $table->dropColumn([
                'num_post',
                'last_post_id',
                'last_post_user_id',
                'last_post_created_at',
            ]);
        });

        Schema::table('forums', function (Blueprint $table): void {
            $table->dropColumn([
                'num_post',
                'num_topic',
                'last_topic_id',
                'last_post_id',
                'last_post_user_id',
                'last_post_created_at',
            ]);
        });
    }
};
