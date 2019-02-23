<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Helpers;

use PhpZip\ZipFile;

class BackupEncryption
{
    const ENCRYPTION_DEFAULT = ZipFile::ENCRYPTION_METHOD_TRADITIONAL;
    const ENCRYPTION_WINZIP_AES_128 = ZipFile::ENCRYPTION_METHOD_WINZIP_AES_128;
    const ENCRYPTION_WINZIP_AES_192 = ZipFile::ENCRYPTION_METHOD_WINZIP_AES_192;
    const ENCRYPTION_WINZIP_AES_256 = ZipFile::ENCRYPTION_METHOD_WINZIP_AES_256;
}
