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

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\ScheduledDowntimeInterface;

class ScheduledDowntime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled_downtime:start {in_minutes}
                               {--expected_downtime_duration= : How many minutes the downtime is expected to take (default: 10)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the "there will be scheduled downtime in x minutes"';

    /** @var WillBeDown object that holds most of the logic */
    protected $will_be_down;

    public function __construct(ScheduledDowntimeInterface $will_be_down)
    {
        parent::__construct();
        $this->will_be_down = $will_be_down;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $in = (int) trim($this->argument('in_minutes'));

        $expected_downtime_in_minutes = $this->option('expected_downtime_duration') ?? 10;

        $this->info("Ok, will tell users that the site is expected to go down in $in minutes, and that it should take approx $expected_downtime_in_minutes minutes to complete");

        $this->checkForErrors($in, $expected_downtime_in_minutes);

        // the important part:
        $this->will_be_down->setScheduledDowntime($in, $expected_downtime_in_minutes);

        $this->outputSuccessMessages($in);
    }

    /**
     * Output some helper messages to the user to explain what was going on.
     *
     * @param $downtime_due_to_start_in_x_minutes
     */
    protected function outputSuccessMessages(int $downtime_due_to_start_in_x_minutes)
    {
        $this->line('Success!');
        $this->line('');
        $this->info('Scheduled downtime message (in '.$downtime_due_to_start_in_x_minutes.' minutes) has been added!');

        $this->line('');

        $this->question('In '.$downtime_due_to_start_in_x_minutes.' minutes YOU must run `php artisan down` - this is NOT automatic!');
        $this->line('');
        $this->question('Once you have done your work and want to make the site live again, please run:');
        $this->question('`php artisan scheduled_downtime:stop`');
        $this->question('(running that command automatically also runs `php artisan up`)');
    }

    /**
     * @param $downtime_due_to_start_in_x_minutes (number of minutes)
     * @param $expected_downtime_in_mins
     * @return bool - false if no error were found. Throws exception if found error.
     * @throws \Exception
     */
    protected function checkForErrors(int $downtime_due_to_start_in_x_minutes, $expected_downtime_in_mins)
    {
        if (! is_numeric($downtime_due_to_start_in_x_minutes) || $downtime_due_to_start_in_x_minutes < 1) {
            throw new \Exception('The minutes must be at least in 1 minute');
        }

        if (! is_numeric($expected_downtime_in_mins) || $expected_downtime_in_mins < 1) {
            throw new \Exception('The expected downtime in minutes is not a valid number');
        }

        // no errors! we can return false
        return false;
    }
}
