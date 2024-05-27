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
        Schema::create('forum_categories', function (Blueprint $table): void {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('position');
            $table->string('slug');
            $table->string('name');
            $table->string('description');

            $table->timestamps();
        });

        DB::table('forum_categories')->insertUsing(
            [
                'id',
                'position',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
            ],
            DB::table('forums')->select([
                'id',
                DB::raw("COALESCE(position, 0) as position"),
                DB::raw("COALESCE(slug, '') as slug"),
                DB::raw("COALESCE(name, '') as name"),
                DB::raw("COALESCE(description, '') as description"),
                'created_at',
                'updated_at',
            ])
                ->whereNull('parent_id')
        );

        Schema::table('forums', function (Blueprint $table): void {
            $table->dropForeign(['parent_id']);
            $table->renameColumn('parent_id', 'forum_category_id');
        });

        DB::table('forums')
            ->whereNull('forum_category_id')
            ->delete();

        Schema::table('forums', function (Blueprint $table): void {
            $table->unsignedSmallInteger('forum_category_id')->nullable(false)->change();

            $table->foreign('forum_category_id')->references('id')->on('forum_categories')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Remove existing duplicates
        DB::table('permissions as p1')
            ->join('permissions as p2', function ($join): void {
                $join->on('p1.id', '<', 'p2.id')
                    ->whereColumn('p1.group_id', '=', 'p2.group_id')
                    ->whereColumn('p1.forum_id', '=', 'p2.forum_id');
            })
            ->delete();

        Schema::table('permissions', function (Blueprint $table): void {
            $table->unique(['group_id', 'forum_id']);
        });
    }
};
