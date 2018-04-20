<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Console\Commands;

use App\Torrent;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class demoSeed extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'demo:seed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seeds fake data for demonstration or testing purposes';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
	    $this->alert('Demo Seeder v1.0 Alpha (Author: Poppabear)');
	    $this->warn('*** This process could take several minutes ***');
	    $this->warn('Press CTRL + C to abort');

	    // Users
		$this->info('Creating User Accounts ...');
		factory(User::class, 500)->create();

        $this->info('Creating Torrents for Accounts ...');
		foreach (User::all() as $user) {

		    // random boolean
		    if ([false,true][rand(0,1)]) {
                factory(Torrent::class, random_int(1, 50))->create([
                    'user_id' => $user->id
                ]);
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [

		];
	}

}
