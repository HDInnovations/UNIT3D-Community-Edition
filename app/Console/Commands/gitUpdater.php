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
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\GitUpdate;
use App\Console\ConsoleTools;

class gitUpdater extends Command
{
    use ConsoleTools;

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

        if (!$this->io->confirm('Would you like to proceed', false)) {
            $this->line('<fg=red>Aborted ...</>');
            die();
        }

        $this->io->writeln('
        Press CTRL + C ANYTIME to abort! Aborting can lead to unexpected results!
        ');

        sleep(1);

        $this->update();

        $this->white('Please report any errors or issues.');

        $this->done();
    }

    private function update()
    {
        $updating = $this->checkForUpdates();

        if (count($updating) > 0) {
            $this->line('<fg=magenta>
            <fg=white>[</><fg=red> !! ATTENTION !! </><fg=white>]</>
            
            We have found files to be updated.
            
            You have 3 options here:
            
            <fg=white>\'Manual\':</><fg=cyan> Update files one by one.</>
            <fg=white>\'Auto\':</><fg=cyan> Update all files automatically.</>
            <fg=white>\'Exit\':</><fg=cyan> Abort the update.</>
            
            <fg=red> Please note if you chose to Update a file you WILL LOSE any custom changes to that file! </>
            
            </>');

            $this->info('Files that need updated:');
            $this->io->listing($updating);

            $choice = $this->io->choice('How would you like to update', ['Manual', 'Auto', 'Exit'], 'Exit');

            if ($choice !== 'Exit') {

                $this->prepare();

                if ($choice === 'Auto') {
                    $this->autoUpdate($updating);
                } else {
                    $this->manualUpdate($updating);
                }

                if ($this->io->confirm('Run new migrations (php artisan migrate)', false)) {
                    $this->migrations();
                }

                if ($this->io->confirm('Compile assets (npm run prod)', false)) {
                    $this->compile();
                }

                if ($this->io->confirm('Install new packages (composer install)', false)) {
                    $this->composer();
                }

                $this->clear();

                $this->call('up');

            } else {
                $this->alertDanger('Aborted Update');
                die();
            }
        } else {
            $this->alertSuccess("No Available Updates Found");
        }
    }

    private function checkForUpdates()
    {
        $this->header("Checking For Updates");

        $this->process('git fetch origin');
        $process = $this->process('git diff ..origin/master --name-only');
        $updating = array_filter(explode("\n", $process->getOutput()), 'strlen');

        $this->magenta("Checking file hashes ... Please wait!");

        foreach ($updating as $index => $file) {
            $sha1 = str_replace("\n", '', $this->process("git rev-parse origin:$file", true)->getOutput());

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

        $this->done();

        return $updating;
    }

    private function prepare()
    {
        $this->call('down', [
            '--message' => "Currently Updating",
            '--retry' => '300'
        ]);

        $this->backup();
    }

    private function autoUpdate($updating)
    {
        $this->alertInfo("Automatic Update");

        foreach ($updating as $file) {

            if (str_contains($file, 'config/')) {

                $this->alertDanger("Configuration File Detected");
                $this->red("Updating $file will cause you to LOSE any changes you might have made to this file!");

                if ($this->io->confirm('Update Configuration File', false)) {
                    $this->updateFile($file);
                }

            } else {
                $this->updateFile($file);
            }
        }

        $this->done();
    }

    private function manualUpdate($updating)
    {
        $this->alertInfo("Manual Update");

        foreach ($updating as $file) {

            if (str_contains($file, 'config/')) {
                $this->alertDanger("Configuration File Detected");
                $this->red("Updating $file will cause you to LOSE any changes you might have made to this file!");

                if ($this->io->confirm('Update Configuration File', false)) {
                    $this->updateFile($file);
                }

            } else if ($this->io->confirm("Update $file", false)) {
                $this->updateFile($file);
            }

        }

        $this->done();
    }

    private function updateFile($file)
    {
        $this->process("git checkout origin/master -- $file");
    }

    private function backup()
    {
        $this->header('Backing Up Files');

        $this->commands([
            'rm -rf ' . storage_path('gitupdate'),
            'mkdir ' . storage_path('gitupdate')
        ], true);

        foreach ($this->paths as $path) {
            $this->validatePath($path);
            $this->createBackupPath($path);
            $this->process($this->copy_command . ' ' . base_path($path) . ' ' . storage_path('gitupdate') . '/' . $path);
        }

        $this->done();
    }

    private function composer()
    {
        $this->header("Installing Composer Packages");

        $this->commands([
            'composer install',
            'composer dump-autoload'
        ]);

        $this->done();
    }

    private function compile()
    {
        $this->header('Compiling Assets ...');

        $this->commands([
            'rm -rf node_modules',
            'npm install',
            'npm run prod'
        ]);

        $this->done();
    }

    private function clear()
    {
        $this->header("Clearing Cache");
        $this->call('clear:all');
        $this->done();
    }

    private function migrations()
    {
        $this->header("Running New Migrations");
        $this->call('migrate');
        $this->done();
    }

    private function validatePath($path)
    {
        if (!is_file(base_path($path)) && !is_dir(base_path($path))) {
            $this->red("The path '$path' is invalid");
            $this->call('up');
            die();
        }
    }

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
