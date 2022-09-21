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

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('category_id')->index('category_id');
            $table->string('type');
            $table->string('imdb')->nullable()->index('imdb');
            $table->string('tvdb')->nullable()->index('tvdb');
            $table->string('tmdb')->nullable()->index('tmdb');
            $table->string('mal')->nullable()->index('mal');
            $table->text('description', 65535);
            $table->integer('user_id')->index('requests_user_id_foreign');
            $table->float('bounty', 22);
            $table->integer('votes')->default(0);
            $table->boolean('claimed')->nullable();
            $table->timestamps();
            $table->integer('filled_by')->nullable()->index('filled_by');
            $table->string('filled_hash')->nullable()->index('filled_hash');
            $table->dateTime('filled_when')->nullable();
            $table->integer('approved_by')->nullable()->index('approved_by');
            $table->dateTime('approved_when')->nullable();
        });
    }
};
