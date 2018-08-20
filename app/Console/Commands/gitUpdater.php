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

use App\GitUpdate;

class gitUpdater extends Command
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
        * Git Updater v2.5 Beta   *
        ***************************
        ');

        $this->line('<fg=cyan>
        THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
        
        IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
        SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
        GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) EVEN IF ADVISED OF THE POSSIBILITY 
        OF SUCH DAMAGE.
        
        WITH THAT SAID YOU CAN BE GUARANTEED THAT YOUR DATABASE WILL NOT BE ALTERED.
        
        <fg=red>BY PROCEEDING YOU AGREE TO THE ABOVE DISCLAIMER! USE AT YOUR OWN RISK!</>
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

        $this->update();

        $this->info('Done ... Please report any errors or issues.');
    }

    private function update()
    {
        $this->info("\n\nChecking for updates ...");

        $updating = $this->checkForUpdates();

        if (count($updating) > 0) {
            $this->line('<fg=magenta>
<fg=white>[</><fg=red> !! ATTENTION !! </><fg=white>]</>

We have found files to be updated.

You have 3 options here:

<fg=white>\'Manual\':</> <fg=cyan> Update files one by one.</>
<fg=white>\'Auto\':</> <fg=cyan> Update all files automatically.</>
<fg=white>\'Exit\':</> <fg=cyan> Abort the update.</>

<fg=red> Please note if you chose to Update a file you WILL LOSE any custom changes to that file! </>

</>');

            $this->info('Below is the list of files that needs updated:');
            $this->io->listing($updating);

            $choice = $this->io->choice('How would you like to update', ['Manual', 'Auto', 'Exit'], 'Exit');

            if ($choice !== 'Exit') {

                $this->prepare();

                if ($choice === 'Auto') {
                    $this->io->writeln("\n\n<fg=white>[</><fg=red> !! Automatic Update !! </><fg=white>]</>");
                    $this->autoUpdate($updating);
                } else {
                    $this->io->writeln("\n\n<fg=white>[</><fg=red> !! Manual Update !! </><fg=white>]</>");
                    $this->manualUpdate($updating);
                }

                $this->migrations();

                $this->compile();

                $this->composer();

                $this->clear();

                $this->call('up');

            } else {
                $this->io->writeln('<fg=white>[</><fg=red> !! Aborted Update !! </><fg=white>]</>');
                die();
            }
        } else {
            $this->info("\n\nNo Available Updates Found\n");
        }
    }

    private function backup()
    {
        $this->warn('Backing up files specified in config/gitupdate.php ...');

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

    private function composer()
    {
        $this->info("\nInstalling Composer packages ...");
        $this->process('composer install');
    }

    private function compile()
    {
        $this->info('Compiling Assets ...');

        $this->commands([
            'rm -rf node_modules',
            'npm install',
            'npm run prod'
        ]);
    }

    private function clear()
    {
        $this->call('clear:all');

        $this->commands([
            'chown -R www-data: storage bootstrap public config',
            'find . -type d -exec chmod 0775 \'{}\' + -or -type f -exec chmod 0664 \'{}\' +'
        ]);
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
                $this->error("'{$command}' timed out.!");
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
    private function validatePath($path)
    {
        if (!is_file(base_path($path)) && !is_dir(base_path($path))) {
            $this->error("\n\nThe path '$path' is invalid ...");
            $this->call('up');
            die();
        }
    }

    /**
     * @param $path
     */
    private function createBackupPath($path)
    {
        if (!is_dir(storage_path("gitupdate/$path")) && !is_file(base_path($path))) {
            mkdir(storage_path("gitupdate/$path"), 0775, true);
        } elseif (is_file(base_path($path)) && dirname($path) !== '.') {
            $path = dirname($path);
            mkdir(storage_path("gitupdate/$path"), 0775, true);
        }
    }

    /**
     * @param $file
     */
    private function updateFile($file)
    {

        if (dirname($file) === 'config') {
            $this->line('<fg=magenta>
<fg=white>[</><fg=red> !! ATTENTION !! </><fg=white>]</>

This next file is a configuration file. If you choose to update this file
you will loose any custom modifications to this file and will likely need to
add your changes back. If you choose not to, you may want to look at the changes
and add in the updated changes manually to this file.

</>');

            if ($this->io->confirm("Update $file", false)) {
                $this->process("git checkout origin/master -- $file", true);
            }
        } else {
            $this->process("git checkout origin/master -- $file", true);
        }

    }

    /**
     * @return array
     */
    private function checkForUpdates()
    {
        $process = $this->process('git fetch origin && git diff ..origin/master --name-only');
        $updating = array_filter(explode("\n", $process->getOutput()), 'strlen');

        foreach ($updating as $index => $file) {
            $sha1 = str_replace("\n", '', $this->process("git rev-parse @:$file")->getOutput());

            $model = GitUpdate::whereName($file)->first();

            if ($model !== null) {
                if ($model->hash !== $sha1) {
                    $model->update(['hash' => $sha1]);
                } else {
                    unset($updating[$index]);
                }
            } else {
                GitUpdate::create([
                    'name' => $file,
                    'hash' => $sha1
                ]);
            }
        }

        return $updating;
    }

    private function prepare(): void
    {
        $this->call('down', [
            '--message' => "Currently Updating",
            '--retry' => '300'
        ]);

        $this->backup();
    }

    /**
     * @param $updating
     */
    private function autoUpdate($updating)
    {
        foreach ($updating as $file) {
            $this->updateFile($file);
        }
    }

    /**
     * @param $updating
     */
    private function manualUpdate($updating)
    {
        foreach ($updating as $file) {
            if ($this->io->confirm("Update $file", false)) {
                $this->updateFile($file);
            }
        }
    }
}
