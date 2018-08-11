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
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class gitUpdate extends Command
{
    /**
     * @var SymfonyStyle $io
     */
    protected $io;

    /**
     * The copy command
     */
    protected $copy_command = 'cp -Rfp';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'git:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes the commands necessary to update your website using git';

    /**
     * @var $paths
     */
    private $paths;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->paths = config('gitupdate.backup');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->input = new ArgvInput();
        $this->output = new ConsoleOutput();

        $this->io = new SymfonyStyle($this->input, $this->output);

        $this->info('
        ***************************
        * Git Updater v2.0 Stable *
        ***************************
        ');

        $this->line('<fg=cyan>
        THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
        
        IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
        SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
        GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) EVEN IF ADVISED OF THE POSSIBILITY 
        OF SUCH DAMAGE.
        
        <fg=red>BY PROCEEDING YOU AGREE TO THE ABOVE DISCLAIMER!</>
        </>');

        $choice = $this->io->choice('Would you like to proceed', ['no', 'yes'], 'no');
        if ($choice === 'no') {
            $this->line('<fg=red>Aborted ...</>');
            die();
        }

        $this->warn('
        Press CTRL + C ANYTIME to abort! Aborting can lead to unexpected results!
        ');

        sleep(3);

        $this->call('down', [
            '--message' => "Currently Updating",
            '--retry' => '300'
        ]);

        $this->git();

        $this->composer();

        $this->migrations();

        $this->compile();

        $this->clear();

        $this->call('up');

        $this->info('Done ... Please report any errors or issues.');
    }

    private function git()
    {
        $process = $this->process('git fetch && git diff --name-only --oneline ..origin');
        $updating = array_filter(explode("\n", $process->getOutput()), 'strlen');

        $process = $this->process('git diff --name-only');
        $diffs = array_filter(explode("\n", $process->getOutput()), 'strlen');

        $results = array_intersect($updating, $diffs);

        $this->info("\n\nUpdating to be current with remote repository ...");

        $this->backup();

        $this->commands([
            'git stash',
            'git checkout master',
            'git fetch origin',
            'git reset --hard origin/master',
            'git pull origin master'
        ]);

        $this->restore();

        if (count($results) > 0) {
            $this->line('<fg=magenta>
            <fg=white>[</><fg=red> !! ATTENTION !! </><fg=white>]</>
            
            We have detected a conflict with some of the files we are trying to update.
            
            This happens when we are trying to update files that you have made custom changes to.
            
            You have 2 options here:
            
            <fg=white>\'Keep\':</> <fg=cyan> Keep YOUR VERSION and manually updated the file with our changes.</>
            <fg=white>\'Update\':</> <fg=cyan> Update with OUR VERSION and manually updated the file with your changes.</>
            
            <fg=red> Please note if you chose to Update you WILL LOSE your changes. </>

            </>');

            $this->info('Below is the list of files that will be affected:');

            $this->io->listing($results);

            $choice = $this->io->choice('Would you like to \'Keep\' or \'Update\'', ['Keep', 'Update'], 'Keep');
            if ($choice === 'Update') {
                foreach ($results as $file) {
                    $this->process("git checkout -- $file");
                }
            }
        }
    }

    private function backup()
    {
        $this->warn('Backing up some stuff ...');

        $this->commands([
            'rm -rf ' . storage_path('gitupdate'),
            'mkdir ' . storage_path('gitupdate')
        ], true);

        foreach ($this->paths as $path) {

            $this->validatePath($path);

            $this->createBackupPath($path);

            $this->process($this->copy_command . ' ' . base_path($path) . ' ' . storage_path('gitupdate') . '/' . $path);
        }
    }

    private function restore()
    {
        $this->warn('Restoring backed up stuff ...');

        foreach ($this->paths as $path) {
            $this->process($this->copy_command . ' ' . storage_path('gitupdate') . DIRECTORY_SEPARATOR . $path . ' ' . base_path(dirname($path) . DIRECTORY_SEPARATOR));
        }
    }

    private function composer()
    {
        $this->info("\nInstalling Composer packages ...");
        $this->process('composer install');
    }

    private function compile()
    {
        $this->info('Compiling Assets ...');

        $this->commands([
            'npm install',
            'npm run prod'
        ]);
    }

    private function clear()
    {
        $this->call('clear:all');
    }

    private function migrations()
    {
        $this->info("\nRunning new migrations ...");

        $this->call('migrate');
    }

    private function commands(array $commands, $silent = false)
    {
        foreach ($commands as $command) {
            $process = $this->process($command, $silent);

            if (!$silent) {
                echo "\n\n";
                $this->warn($process->getOutput());
            }
        }
    }

    private function process($command, $silent = false)
    {
        if (!$silent) {
            $this->io->writeln("\n<fg=cyan>$command</>");
            $bar = $this->progressStart();
        }

        $process = new Process($command);
        $process->setTimeout(3600);
        $process->start();

        while ($process->isRunning()) {
            try {
                $process->checkTimeout();
            } catch (ProcessTimedOutException $e) {
                $this->error("'{$command}' timed out. Please run manually!");
            }

            if (!$silent) {
                $bar->advance();
            }

            usleep(200000);
        }

        if (!$silent) {
            $this->progressStop($bar);
        }

        $process->stop();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            die();
        }

        return $process;
    }

    /**
     * @return ProgressBar
     */
    protected function progressStart()
    {
        $bar = $this->io->createProgressBar();
        $bar->setBarCharacter('<fg=magenta>=</>');
        $bar->setFormat('[%bar%] (<fg=cyan>%message%</>)');
        $bar->setMessage('Please Wait ...');
        //$bar->setRedrawFrequency(20); todo: may be useful for platforms like CentOS
        $bar->start();

        return $bar;
    }

    /**
     * @param $bar
     */
    protected function progressStop(ProgressBar $bar)
    {
        $bar->setMessage("<fg=green>Done!</>");
        $bar->finish();
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

    /**
     * @param $path
     */
    private function validatePath($path): void
    {
        if (!is_file(base_path($path)) && !is_dir(base_path($path))) {
            $this->error("The path '$path' is invalid ...");
            $this->call('up');
            die();
        }
    }

    /**
     * @param $path
     */
    private function createBackupPath($path): void
    {
        if (!is_dir(storage_path("gitupdate/$path")) && !is_file(base_path($path))) {
            $arr = explode('/', $path);
            $parent = null;
            foreach ($arr as $dir) {
                $_dir = $parent ? $parent . '/' . $dir : $dir;
                $this->commands(['mkdir ' . storage_path('gitupdate') . '/' . $_dir], true);
                $parent = $_dir;
            }
        }
    }
}
