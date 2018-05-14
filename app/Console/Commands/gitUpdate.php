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
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class gitUpdate extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'git:update 
    {file? : The path of the file you want to update} 
    {--backup : To create a backup before update}
    {--no-compile : To opt out of compiling assets}';

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
        $this->alert('Git Updater v1.8 Beta by Poppabear');
        $this->warn('Press CTRL + C to abort');

        sleep(5);

        $backup = $this->option('backup');
        $no_compile = $this->option('no-compile');

        if ($backup) {
            $this->createBackup();
        }

        $this->runGitCommands();

        $this->runComposerCommands();

        $this->runNewMigrations();

        if (!$no_compile) {
            $this->compileAssets();
        } else {
            $this->warn('!!! Skipping Asset Compiling !!!');
        }

        $this->clearCache();

        $this->info('Done ... Please report any errors or issues.');

    }

    private function createBackup()
    {
        $this->info('Creating Backup ...');
        $this->warn('*** This process could take a few minutes ***');
        try {
            // start the backup process
            $this->call('backup:run');

            // log the results
            info("A new backup was initiated from the git:update command ... ");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function runComposerCommands(): void
    {
        $this->comment('Installing Composer packages ...');
        $this->alert('THIS CAN TAKE A FEW MINUTES');

        $commands = [
            'composer install',
        ];

        $this->processCommands($commands);
    }

    private function compileAssets(): void
    {
        $this->comment('Compiling JS and Style assets ...');
        $this->alert('THIS CAN TAKE A FEW MINUTES');

        $commands = [
            'npm install',
            'npm run prod',
            'git add .' // we stage the compiled files
        ];

        $this->processCommands($commands);
    }

    private function clearCache(): void
    {
        $this->call('clear:all');
    }

    private function runNewMigrations(): void
    {
        $this->comment('Running new migrations ...');
        $this->call('migrate');
    }

    private function runGitCommands(): void
    {
        $file = $this->argument('file');

        if ($file !== null) {
            $this->info("Updating file {$file} ...");

            $commands = [
                'git stash',
                'git fetch origin',
                "git checkout origin/master -- {$file}",
                'git stash pop'
            ];
        } else {
            $this->info('Updating ...');
            $commands = [
                'git fetch origin',
                'git checkout -- public/js',
                'git checkout -- public/css',
                'git checkout -- app/Console/Commands/gitUpdate.php',
                'git checkout -- package.json',
                'git checkout -- package-lock.json',
                'git checkout -- composer.json',
                'git checkout -- composer.lock',
                'git add .',
                'git stash',
                'git rebase origin/master',
                'git stash pop',
            ];
        }

        $this->warn('*** This process could take a few minutes ***');

        $this->processCommands($commands);
    }

    private function processCommands(array $commands)
    {
        foreach ($commands as $command) {
            $process = new Process($command);
            $process->setTimeout(150);

            $process->start();

            try {
                $process->wait();
            } catch (RuntimeException $e) {
                $this->error("'{$command}' timed out. Please run manually!");
            }

            $this->info($process->getOutput());
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
