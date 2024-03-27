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

use Illuminate\Console\Command;
use RuntimeException;
use Exception;

class DbLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads a pristine copy of the database (useful for testing locally)';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $input = config('database.pristine-db-file');
        $db = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Necessary to avoid warning about supplying password on CLI.

        putenv(sprintf('MYSQL_PWD=%s', $password));

        $cmd = sprintf(
            'mysql -u %s %s < %s',
            escapeshellarg((string) $user),
            escapeshellarg((string) $db),
            escapeshellarg((string) $input)
        );

        $return = null;

        $output = null;

        exec($cmd, $output, $return);

        throw_if($return !== 0, new RuntimeException(sprintf('Could not load database from file %s', $input)));
    }
}
