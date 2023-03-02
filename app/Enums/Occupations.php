<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

declare(strict_types=1);

namespace App\Enums;

enum Occupations: int
{
    case CREATOR = 1;
    case DIRECTOR = 2;
    case WRITER = 3;
    case PRODUCER = 4;
    case COMPOSER = 5;
    case CINEMATOGRAPHER = 6;
    case EDITOR = 7;
    case PRODUCTION_DESIGNER = 8;
    case ART_DIRECTOR = 9;
    case ACTOR = 10;

    public static function from_tmdb_job($job_name): ?static
    {
        return match ($job_name) {
            "Director"   => static::DIRECTOR,
            "Screenplay" => static::WRITER,
            "Producer", "Co-Producer", "Associate Producer" => static::PRODUCER,
            "Original Music Composer" => static::COMPOSER,
            "Director of Photography" => static::CINEMATOGRAPHER,
            "Editor"                  => static::EDITOR,
            "Production Design"       => static::PRODUCTION_DESIGNER,
            "Art Direction"           => static::ART_DIRECTOR,
            default                   => null,
        };
    }
}
