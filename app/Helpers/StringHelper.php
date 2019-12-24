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
use Illuminate\Translation\Translator;

final class StringHelper
{
    /**
     * @var int
     */
    private const KIB = 1_024;

    /**
     * @var int
     */
    private const MIB = 1_024 * 1_024;

    /**
     * @var int
     */
    private const GIB = 1_024 * 1_024 * 1_024;

    /**
     * @var int
     */
    private const TIB = 1_024 * 1_024 * 1_024 * 1_024;

    /**
     * @var int
     */
    private const PIB = 1_024 * 1_024 * 1_024 * 1_024 * 1_024;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private Translator $translator;

    public function __construct(Repository $configRepository, Translator $translator)
    {
        $this->configRepository = $configRepository;
        $this->translator = $translator;
    }

    public static function generateRandomString($length = 20): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public static function formatBytes($bytes, $precision = 2): string
    {
        $bytes = max($bytes, 0);
        $suffix = 'B';
        $value = $bytes;
        if ($bytes >= self::PIB) {
            $suffix = 'PiB';
            $value = $bytes / self::PIB;
        } elseif ($bytes >= self::TIB) {
            $suffix = 'TiB';
            $value = $bytes / self::TIB;
        } elseif ($bytes >= self::GIB) {
            $suffix = 'GiB';
            $value = $bytes / self::GIB;
        } elseif ($bytes >= self::MIB) {
            $suffix = 'MiB';
            $value = $bytes / self::MIB;
        } elseif ($bytes >= self::KIB) {
            $suffix = 'KiB';
            $value = $bytes / self::KIB;
        }

        return round($value, $precision).' '.$suffix;
    }

    /**
     * @method timeRemaining
     *
     * @param  time  $seconds  in bigInt
     *
     * @return string
     */
    public static function timeRemaining($seconds): string
    {
        $minutes = 0;
        $hours = 0;
        $days = 0;
        $weeks = 0;
        $months = 0;
        $years = 0;

        $seconds = $this->configRepository->get('hitrun.seedtime') - $seconds;

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
        $years = ($years === 0) ? '' : $years.$this->translator->trans('common.abbrev-years');
        $months = ($months === 0) ? '' : $months.$this->translator->trans('common.abbrev-months');
        $weeks = ($weeks === 0) ? '' : $weeks.$this->translator->trans('common.abbrev-weeks');
        $days = ($days === 0) ? '' : $days.$this->translator->trans('common.abbrev-days');
        $hours = ($hours === 0) ? '' : $hours.$this->translator->trans('common.abbrev-hours');
        $minutes = ($minutes === 0) ? '' : $minutes.$this->translator->trans('common.abbrev-minutes');
        $seconds = ($seconds == 0) ? '' : $seconds.$this->translator->trans('common.abbrev-seconds');

        return $years.$months.$weeks.$days.$hours.$minutes.$seconds;
    }

    /**
     * @method timeElapsed
     *
     * @param  time  $seconds  in bigInt
     *
     * @return string
     */
    public static function timeElapsed($seconds): string
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
        $years = ($years === 0) ? '' : $years.$this->translator->trans('common.abbrev-years');
        $months = ($months === 0) ? '' : $months.$this->translator->trans('common.abbrev-months');
        $weeks = ($weeks === 0) ? '' : $weeks.$this->translator->trans('common.abbrev-weeks');
        $days = ($days === 0) ? '' : $days.$this->translator->trans('common.abbrev-days');
        $hours = ($hours === 0) ? '' : $hours.$this->translator->trans('common.abbrev-hours');
        $minutes = ($minutes === 0) ? '' : $minutes.$this->translator->trans('common.abbrev-minutes');
        $seconds = ($seconds == 0) ? '' : $seconds.$this->translator->trans('common.abbrev-seconds');

        return $years.$months.$weeks.$days.$hours.$minutes.$seconds;
    }

    public static function ordinal($number): string
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number.'th';
        } else {
            return $number.$ends[$number % 10];
        }
    }
}
