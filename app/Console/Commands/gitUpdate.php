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

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

class gitUpdate extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'git:update {file? : The path of the file you want to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes the commands necessary to update your website using git without loosing changes.';

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
        $this->alert('Git Updater v1.0 Beta (Author: Poppabear)');
        $this->warn('Press CTRL + C to abort');

        sleep(5);

        $this->alert('We are now going to create a backup ...');
        $this->warn('*** This process could take a few minutes ***');

        $this->createBackup();

        $this->alert('We are now going to run a series of git commands ...');
        $this->warn('*** This process could take a few minutes ***');

        $this->runGitCommands();

        $this->runNewMigrations();

        $this->clearCache();

        $this->compileAssets();

        $this->info('Done ...');
        $this->alert('Please report any errors or issues.');

    }

    private function createBackup()
    {
        try {
            // start the backup process
            $this->call('backup:run');

            // log the results
            info("A new backup was initiated from the git:update command ... ");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
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

    private function compileAssets(): void
    {
        $this->comment('Compiling JS and Style assets ...');
        $process = new Process('npm run dev');
        $process->start();

        $process->wait();

        $this->info($process->getOutput());
    }

    private function clearCache(): void
    {
        $this->comment('Clearing several common cache\'s ...');
        $this->call('view:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('cache:clear');
    }

    private function runNewMigrations(): void
    {
        $this->comment('Running new migrations ...');
        $this->call('migrate');
    }

    private function runGitCommands(): void
    {
        if ($this->arguments() > 0) {
            $file = $this->argument('file');

            if ($file !== null) {
                $commands = [
                    'git stash',
                    'git fetch origin',
                    "git checkout origin/master -- {$file}",
                    'git stash pop'
                ];
            }

        } else {
            $commands = [
                'git reset --mixed',
                'git stash',
                'git fetch origin',
                'git rebase origin/master',
                'git stash pop'
            ];
        }

        foreach ($commands as $command) {
            $process = new Process($command);
            $process->start();

            $process->wait();

            $this->info($process->getOutput());
        }
    }
}
