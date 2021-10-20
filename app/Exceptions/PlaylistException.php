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

namespace App\Exceptions;

use Throwable;

class PlaylistException extends \Exception
{
    protected const ERROR_MSG = [
        // Error message from Private Playlists Access Control System
        100 => "This is a private playlist. You do not have access to other users' private playlists!",

        // Test Message
        998 => 'Internal server error :msg',
        999 => ':test',
    ];

    /**
     * TrackerException constructor.
     *
     * @param array|null      $replace
     * @param \Throwable|null $throwable
     */
    public function __construct(int $code = 999, array $replace = null, Throwable $throwable = null)
    {
        $message = self::ERROR_MSG[$code];
        if ($replace) {
            foreach ($replace as $key => $value) {
                $message = \str_replace($key, $value, $message);
            }
        }

        parent::__construct($message, $code, $throwable);
    }
}
