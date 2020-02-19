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
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('email');
            $table->longText('referrer')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('moderated_at')->nullable();
            $table->unsignedBigInteger('moderated_by')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->unique('email', 'applications_email_unique');
            $table->index('accepted_by');
            $table->index('moderated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}