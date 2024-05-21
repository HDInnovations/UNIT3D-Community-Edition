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

class StringHelper
{
    private const array units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

    public static function formatBytes(int|float $bytes = 0, int $precision = 2): string
    {
        $minus = false;

        if ($bytes < 0) {
            $minus = true;
            $bytes *= -1;
        }

        for ($i = 0; ($bytes / 1024) > 0.9 && ($i < \count(self::units) - 1); $i++) {
            $bytes /= 1024;
        }

        $result = round($bytes, $precision);

        if ($minus) {
            $result *= -1;
        }

        return $result."\u{a0}".self::units[$i];
    }

    public static function timeElapsed(int $seconds): string
    {
        $minutes = 0;
        $hours = 0;
        $days = 0;
        $weeks = 0;
        $months = 0;
        $years = 0;

        if ($seconds == 0) {
            return 'N/A';
        }

        while ($seconds >= 31_536_000) {
            $years++;
            $seconds -= 31_536_000;
        }

        while ($seconds >= 2_592_000) {
            $months++;
            $seconds -= 2_592_000;
        }

        while ($seconds >= 604_800) {
            $weeks++;
            $seconds -= 604_800;
        }

        while ($seconds >= 86_400) {
            $days++;
            $seconds -= 86_400;
        }

        while ($seconds >= 3_600) {
            $hours++;
            $seconds -= 3_600;
        }

        while ($seconds >= 60) {
            $minutes++;
            $seconds -= 60;
        }

        $years = ($years === 0) ? '' : $years.trans('common.abbrev-years');
        $months = ($months === 0) ? '' : $months.trans('common.abbrev-months');
        $weeks = ($weeks === 0) ? '' : $weeks.trans('common.abbrev-weeks');
        $days = ($days === 0) ? '' : $days.trans('common.abbrev-days');
        $hours = ($hours === 0) ? '' : $hours.trans('common.abbrev-hours');
        $minutes = ($minutes === 0) ? '' : $minutes.trans('common.abbrev-minutes');
        $seconds = ($seconds == 0) ? '' : $seconds.trans('common.abbrev-seconds');

        return $years.$months.$weeks.$days.$hours.$minutes.$seconds;
    }
}
