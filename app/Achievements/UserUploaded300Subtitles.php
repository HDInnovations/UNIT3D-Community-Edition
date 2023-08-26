<?php

namespace App\Achievements;

use Assada\Achievements\Achievement;

class UserUploaded300Subtitles extends Achievement
{
    /**
     * The achievement name.
     *
     * @var string
     */
    public $name = 'UserUploaded300Subtitles';

    /**
     * A small description for the achievement.
     *
     * @var string
     */
    public $description = 'You have made 300 subtitle uploads!';

    /**
     * The amount of "points" this user need to obtain in order to complete this achievement.
     *
     * @var int
     */
    public $points = 300;
}
