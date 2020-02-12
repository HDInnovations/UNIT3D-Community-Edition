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

namespace App\Console;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

trait ConsoleTools
{
    /**
     * @var SymfonyStyle
     */
    protected $io;

    private function cyan($line)
    {
        $this->io->writeln("\n<fg=cyan>$line</>");
    }

    private function white($line)
    {
        $this->io->writeln("\n$line");
    }

    private function magenta($line)
    {
        $this->io->writeln("\n<fg=magenta>$line</>");
    }

    private function green($line)
    {
        $this->io->writeln("\n<fg=green>$line</>");
    }

    private function red($line)
    {
        $this->io->writeln("\n<fg=red>$line</>");
    }

    private function blue($line)
    {
        $this->io->writeln("\n<fg=blue>$line</>");
    }

    private function done()
    {
        $this->green('<fg=white>[</>Done<fg=white>]</>');
    }

    private function header($line)
    {
        $this->blue(str_repeat('=', 50));
        $this->io->write($line);
        $this->blue(str_repeat('=', 50));
    }

    private function alertSuccess($line)
    {
        $this->io->writeln("\n<fg=white>[</><fg=green> !! $line !! </><fg=white>]</>");
    }

    private function alertDanger($line)
    {
        $this->io->writeln("\n<fg=white>[</><fg=red> !! $line !! </><fg=white>]</>");
    }

    private function alertInfo($line)
    {
        $this->io->writeln("\n<fg=white>[</><fg=cyan> !! $line !! </><fg=white>]</>");
    }

    private function alertWarning($line)
    {
        $this->io->writeln("\n<fg=white>[</><fg=yellow> !! $line !! </><fg=white>]</>");
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
            $this->cyan($command);
            $bar = $this->progressStart();
        }

        $process = new Process($command);
        $process->setTimeout(3600);
        $process->start();

        while ($process->isRunning()) {
            try {
                $process->checkTimeout();
            } catch (ProcessTimedOutException $e) {
                $this->red("'{$command}' timed out.!");
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
            $this->red($process->getErrorOutput());
            //die();
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
        $bar->setMessage('<fg=green>Done!</>');
        $bar->finish();
    }
}
