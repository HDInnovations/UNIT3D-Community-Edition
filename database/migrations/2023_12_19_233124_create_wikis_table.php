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
        Schema::create('wikis', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->longText('content');
            $table->unsignedInteger('category_id')->index('wikis_category_id_index');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('wiki_categories')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
