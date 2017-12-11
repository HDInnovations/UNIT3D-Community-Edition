<?php

namespace App\Services\Contracts;

interface MovieTvInterface
{
    /**
     * Find Movie or Tv using IMDB id
     *
     * @param array $keys
     * @param null|string $type
     * @return array
     */
    public function find($keys, $type = null);

    /**
     * @param $id
     * @return \Bhutanio\Movietvdb\Data\Movie
     */
    public function movie($id);

    /**
     * @param $id
     * @return \Bhutanio\Movietvdb\Data\Tv
     */
    public function tv($id);

    /**
     * @param $id
     * @return array
     */
    public function person($id);
}
