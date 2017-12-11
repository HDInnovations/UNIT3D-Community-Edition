<?php

namespace App\Services\Data;

use Carbon\Carbon;

class Person
{
    public $imdb;

    public $tmdb;

    public $name;

    public $aka;

    public $gender;

    public $birthday;

    public $deathday;

    public $placeOfBirth;

    public $biography;

    public $photo;

    public $photos;

    public $character;
    public $order;
    public $job;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (is_array($value) && !count($value)) {
                    $value = null;
                }
                $this->$key = $value;
            }
        }

        $this->birthday = $this->birthday ? new Carbon($this->birthday) : null;
        $this->deathday = $this->deathday ? new Carbon($this->deathday) : null;
    }
}
