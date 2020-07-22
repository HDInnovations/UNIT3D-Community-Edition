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

use Illuminate\Support\Collection;
use PhpZip\ZipFile;
use ZipArchive;

class BackupPassword
{
    /**
     * Path to .zip-file.
     *
     * @var string
     */
    public $path;

    /**
     * The chosen password.
     *
     * @var string
     */
    protected $password;

    /**
     * Read the .zip, apply password and encryption, then rewrite the file.
     *
     * @param \App\Helpers\BackupEncryption $encryption
     * @param string                        $path       the path to the .zip-file
     *
     * @throws \PhpZip\Exception\ZipException
     */
    public function __construct(BackupEncryption $backupEncryption, string $path)
    {
        $this->password = \config('backup.security.password');

        if (! $this->password) {
            return $this->path = $path;
        }

        // If ZipArchive is enabled
        if (\class_exists('ZipArchive') && \in_array('setEncryptionIndex', \get_class_methods('ZipArchive'))) {
            \consoleOutput()->info('Applying password and encryption to zip using ZipArchive...');
            $this->makeZipArchive($backupEncryption, $path);
        }

        // Fall back on PHP-driven ZipFile
        else {
            \consoleOutput()->info('Applying password and encryption to zip using ZipFile...');
            $this->makeZipFile($backupEncryption, $path);
        }

        \consoleOutput()->info('Successfully applied password and encryption to zip.');
    }

    /**
     * Use native PHP ZipArchive.
     *
     * @param \App\Helpers\BackupEncryption $encryption
     * @param string                        $path
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function makeZipArchive(BackupEncryption $backupEncryption, string $path): void
    {
        $encryptionConstant = $backupEncryption->getEncryptionConstant(
            \config('backup.security.encryption'),
            'ZipArchive'
        );

        $zipArchive = new ZipArchive();

        $zipArchive->open($path, ZipArchive::OVERWRITE);
        $zipArchive->addFile($path, 'backup.zip');
        $zipArchive->setPassword($this->password);
        Collection::times($zipArchive->numFiles, function ($i) use ($zipArchive, $encryptionConstant) {
            $zipArchive->setEncryptionIndex($i - 1, $encryptionConstant);
        });
        $zipArchive->close();

        $this->path = $path;
    }

    /**
     * Use PhpZip\ZipFile-package to create the zip.
     *
     * @param \App\Helpers\BackupEncryption $encryption
     *
     * @throws \PhpZip\Exception\ZipException
     *
     * @return void
     */
    protected function makeZipFile(BackupEncryption $backupEncryption, string $path): void
    {
        $encryptionConstant = $backupEncryption->getEncryptionConstant(
            \config('backup.security.encryption'),
            'ZipFile'
        );

        $zipFile = new ZipFile();
        $zipFile->addFile($path, 'backup.zip', ZipFile::METHOD_DEFLATED);
        $zipFile->setPassword($this->password, $encryptionConstant);
        $zipFile->saveAsFile($path);
        $zipFile->close();

        $this->path = $path;
    }
}
