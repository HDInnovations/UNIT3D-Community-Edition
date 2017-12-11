<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     BluCrew
 */
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAchievementProgressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('achievement_progress', function(Blueprint $table)
		{
			$table->char('id', 36)->primary();
			$table->integer('achievement_id')->unsigned()->index('achievement_progress_achievement_id_foreign');
			$table->integer('achiever_id')->unsigned();
			$table->string('achiever_type');
			$table->integer('points')->unsigned()->default(0);
			$table->dateTime('unlocked_at')->nullable();
			$table->timestamps();
			$table->index(['achiever_id','achiever_type']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('achievement_progress');
	}

}
