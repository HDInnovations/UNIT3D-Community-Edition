<?php

namespace App\Services\Data;

use Carbon\Carbon;

class Episode
{
    public $season;

    public $episode;

    public $title;

    public $releaseDate;

    public $plot;

    public $photo;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (is_array($value) && !count($value)) {
                    $value = null;
                }
                $this->$key = !empty($value) ? $value : null;
            }
        }

        if ($this->releaseDate instanceof \DateTime) {
            $release_date = $this->releaseDate ? (new Carbon())->instance($this->releaseDate) : null;
        } else {
            $release_date = $this->releaseDate ? new Carbon($this->releaseDate) : null;
        }
        $this->releaseDate = $release_date;
    }
}
