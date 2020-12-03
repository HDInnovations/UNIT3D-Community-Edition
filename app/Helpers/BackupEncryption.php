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

class BackupEncryption
{
    /**
     * Default encryption contants.
     *
     * @var string
     */
    const ENCRYPTION_DEFAULT = 'default';

    /**
     * AES-128 encryption contants.
     *
     * @var string
     */
    const ENCRYPTION_WINZIP_AES_128 = 'aes_128';

    /**
     * AES-192 encryption contants.
     *
     * @var string
     */
    const ENCRYPTION_WINZIP_AES_192 = 'aes_192';

    /**
     * AES-256 encryption contants.
     *
     * @var string
     */
    const ENCRYPTION_WINZIP_AES_256 = 'aes_256';

    /**
     * ZipArchive encryption constants; stores as simple string for PHP < 7.2
     * backwards compatability.
     *
     * @var array
     */
    private $zipArchiveOptions = [
        self::ENCRYPTION_DEFAULT        => '257',
        self::ENCRYPTION_WINZIP_AES_128 => '257',
        self::ENCRYPTION_WINZIP_AES_192 => '258',
        self::ENCRYPTION_WINZIP_AES_256 => '259',
    ];

    /**
     * ZipFile encryption constants.
     *
     * @var array
     */
    private $zipFileOptions = [
        self::ENCRYPTION_DEFAULT        => \PhpZip\Constants\ZipEncryptionMethod::PKWARE,
        self::ENCRYPTION_WINZIP_AES_128 => \PhpZip\Constants\ZipEncryptionMethod::WINZIP_AES_128,
        self::ENCRYPTION_WINZIP_AES_192 => \PhpZip\Constants\ZipEncryptionMethod::WINZIP_AES_192,
        self::ENCRYPTION_WINZIP_AES_256 => \PhpZip\Constants\ZipEncryptionMethod::WINZIP_AES_256,
    ];

    /**
     * Retrive appropriate encryption constant.
     *
     * @param string $type
     * @param string $engine
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getEncryptionConstant($type, $engine)
    {
        if ($engine == 'ZipArchive' && isset($this->zipArchiveOptions[$type])) {
            return $this->zipArchiveOptions[$type];
        }
        if ($engine == 'ZipFile' && isset($this->zipFileOptions[$type])) {
            return $this->zipFileOptions[$type];
        }

        throw new \RuntimeException('Encryption key not set or invalid value', 1);
    }
}
