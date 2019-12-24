<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Helpers;

use Illuminate\Contracts\Config\Repository;
use PhpZip\ZipFile;

final class BackupPassword
{
    /**
     * Path to .zip-fil.
     * @var string
     */
    public string $path;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    /**
     * Read the .zip, apply password and encryption, then rewrite the file.
     *
     * @param  string  $path  the path to the .zip-file
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     */
    public function __construct(string $path, Repository $configRepository)
    {
        consoleOutput()->info('Applying password and encryption to zip...');

        // Create a new zip, add the zip from spatie/backup, encrypt and resave/overwrite
        $zipFile = new ZipFile();
        $zipFile->addFile($path, 'backup.zip', ZipFile::METHOD_DEFLATED);
        $zipFile->setPassword($this->configRepository->get('backup.security.password'), $this->configRepository->get('backup.security.encryption'));
        $zipFile->saveAsFile($path);
        $zipFile->close();

        consoleOutput()->info('Successfully applied password and encryption to zip.');

        $this->path = $path;
        $this->configRepository = $configRepository;
    }
}
