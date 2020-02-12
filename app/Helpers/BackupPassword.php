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

namespace App\Helpers;

use PhpZip\ZipFile;

class BackupPassword
{
    /**
     * Path to .zip-fil.
     *
     * @var string
     */
    public $path;

    /**
     * Read the .zip, apply password and encryption, then rewrite the file.
     *
     * @param string $path the path to the .zip-file
     */
    public function __construct(string $path)
    {
        consoleOutput()->info('Applying password and encryption to zip...');

        // Create a new zip, add the zip from spatie/backup, encrypt and resave/overwrite
        $zipFile = new ZipFile();
        $zipFile->addFile($path, 'backup.zip', ZipFile::METHOD_DEFLATED);
        $zipFile->setPassword(config('backup.security.password'), config('backup.security.encryption'));
        $zipFile->saveAsFile($path);
        $zipFile->close();

        consoleOutput()->info('Successfully applied password and encryption to zip.');

        $this->path = $path;
    }
}
