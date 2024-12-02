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
        Schema::create('post_tips', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('sender_id')->nullable();
            $table->unsignedInteger('recipient_id')->nullable();
            $table->integer('post_id')->nullable();
            $table->decimal('bon', 22, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('recipient_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnUpdate()->nullOnDelete();
        });

        DB::table('post_tips')->insertUsing(
            ['id', 'bon', 'sender_id', 'recipient_id', 'post_id', 'created_at'],
            DB::table('bon_transactions')
                ->select([
                    'id',
                    'cost',
                    'sender_id',
                    'receiver_id',
                    DB::raw('CASE WHEN EXISTS(SELECT * FROM posts WHERE id = post_id) THEN post_id END'),
                    'created_at',
                ])
                ->where('name', '=', 'tip')
                ->whereNotNull('post_id')
        );

        DB::table('bon_transactions')
            ->where('name', '=', 'tip')
            ->whereNotNull('post_id')
            ->delete();

        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->dropColumn('post_id');
        });
    }
};
