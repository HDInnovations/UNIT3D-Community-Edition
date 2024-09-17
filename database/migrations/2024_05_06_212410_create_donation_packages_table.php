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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_packages', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('position')->index();
            $table->string('name');
            $table->text('description');
            $table->decimal('cost', 6, 2);
            $table->unsignedBigInteger('upload_value')->nullable();
            $table->unsignedBigInteger('invite_value')->nullable();
            $table->unsignedBigInteger('bonus_value')->nullable();
            $table->unsignedBigInteger('donor_value')->nullable();
            $table->boolean('is_active')->index();
            $table->timestamps();
        });
    }
};
