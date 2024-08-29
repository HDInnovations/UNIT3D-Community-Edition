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
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('can_chat')->nullable()->change();
            $table->boolean('can_comment')->nullable()->change();
            $table->boolean('can_invite')->nullable()->change();
            $table->boolean('can_request')->nullable()->after('can_invite')->change();
            $table->boolean('can_upload')->nullable()->change();
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('can_chat')->after('is_refundable');
            $table->boolean('can_comment')->after('can_chat');
            $table->boolean('can_invite')->after('can_comment');
            $table->boolean('can_request')->after('can_invite');
        });

        DB::table('users')->update([
            'can_chat'    => null,
            'can_comment' => null,
            'can_invite'  => null,
            'can_request' => null,
            'can_upload'  => null,
        ]);

        DB::table('groups')
            ->whereNotIn('slug', [
                'validating',
                'guest',
                'banned',
                'bot',
                'leech',
                'disabled',
                'pruned',
            ])
            ->update([
                'can_comment' => true,
                'can_chat'    => true,
                'can_request' => true,
                'can_invite'  => true,
            ]);
    }
};
