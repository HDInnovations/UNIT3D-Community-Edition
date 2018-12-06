<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('album_id')->unsigned();
            $table->string('image');
            $table->string('description');
            $table->string('type');
            $table->integer('downloads')->nullable();
            $table->foreign('album_id')->references('id')->on('albums')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}
