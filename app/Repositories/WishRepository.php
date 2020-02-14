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

namespace App\Repositories;

use App\Interfaces\WishInterface;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Wish;
use App\Services\Clients\OmdbClient;

class WishRepository implements WishInterface
{
    /**
     * @var Wish
     */
    private $wish;

    /**
     * @var User
     */
    private $user;

    /**
     * @var OmdbClient
     */
    private $client;

    /**
     * @var Torrent
     */
    private $torrent;

    /**
     * WishRepository constructor.
     *
     * @param Wish       $wish
     * @param User       $user
     * @param OmdbClient $client
     * @param Torrent    $torrent
     */
    public function __construct(Wish $wish, User $user, OmdbClient $client, Torrent $torrent)
    {
        $this->wish = $wish;
        $this->user = $user;
        $this->client = $client;
        $this->torrent = $torrent;
    }

    /**
     * @param null $paginate
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($paginate = null)
    {
        return $paginate ? $this->wish->paginate($paginate) : $this->wish->all();
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->wish->create($data);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id)
    {
        return $this->wish->find($id);
    }

    /**
     * @param $title
     *
     * @return mixed
     */
    public function findByTitle($title)
    {
        return $this->wish->where('title', '=', $title)->first();
    }

    /**
     * @param $uid
     * @param $id
     *
     * @return bool
     */
    public function exists($uid, $id)
    {
        return (bool) $this->user->find($uid)
            ->wishes()
            ->where('imdb', '=', $id)
            ->first();
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function isGranted($id)
    {
        $id = str_replace('tt', '', $id);

        return (bool) $this->torrent
            ->where('imdb', '=', $id)
            ->where('seeders', '>', 0)
            ->where('status', '=', 1)
            ->first();
    }

    /**
     * @param $id
     *
     * @return null|string
     */
    public function getSource($id)
    {
        if ($this->isGranted($id)) {
            $id = str_replace('tt', '', $id);
            $source = $this->torrent
                ->where('imdb', '=', $id)
                ->where('seeders', '>', 0)
                ->where('status', '=', 1)
                ->first();

            return route('torrent', ['id' => $source->id]);
        }

        return $this->findById($id)->source ?? null;
    }

    /**
     * @param $uid
     *
     * @return mixed
     */
    public function getUserWishes($uid)
    {
        return $this->user->find($uid)->wishes()->paginate(10);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    /**
     * @param $imdb
     *
     * @return array|mixed|null
     */
    public function omdbRequest($imdb)
    {
        return $this->client->find(['imdb' => $imdb]);
    }
}
