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

namespace App\Services\Tmdb;

use App\Jobs\ProcessMovieJob;
use App\Jobs\ProcessTvJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class TMDBScraper implements ShouldQueue
{
    use SerializesModels;

    public function __construct()
    {
    }

    public function tv(int $id): void
    {
        ProcessTvJob::dispatch($id);
    }

    public function movie(int $id): void
    {
        ProcessMovieJob::dispatch($id);
    }
}
