<?php

namespace App\Services\Data;

use Carbon\Carbon;

class Tv extends Movie
{
    /**
     * @var array
     */
    public $tvdb;

    /**
     * @var array
     */
    public $episodes;

    /**
     * @var Carbon
     */
    public $endDate;

    /**
     * @var bool
     */
    public $ended;

    /**
     * @var string
     */
    public $network;

    /**
     * @var array
     */
    public $creators;

    /**
     * @var float
     */
    public $tvdbRating;

    /**
     * @var int
     */
    public $tvdbVotes;

    public function __construct($data = [])
    {
        parent::__construct($data);

        if($this->endDate instanceof \DateTime) {
            $this->endDate = $this->endDate ? (new Carbon())->instance($this->endDate) : null;
        } else {
            $this->endDate = $this->endDate ? (new Carbon($this->endDate)) : null;
        }
        $this->ended = $this->endDate ? true : $this->ended;
    }

}
