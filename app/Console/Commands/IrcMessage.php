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

namespace App\Console\Commands;

use App\Bots\IRCAnnounceBot;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class IrcMessage extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'irc:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Messages an IRC Channel';

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
        $this->info('Messaging '.$this->argument('channel').': '.$this->argument('message'));
        $bot = new IRCAnnounceBot();
        $bot->message($this->argument('channel'), $this->argument('message'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['channel', InputArgument::REQUIRED, 'Channel that you would like to message'],
            ['message', InputArgument::REQUIRED, 'Message you would like to send'],
        ];
    }

    /*
     * Get the console command options.
     *
     * @return array
     */
    // protected function getOptions()
    // {
    // 	return [
    // 		['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
    // 	];
    // }
}
