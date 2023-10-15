<?php

namespace App\Achievements;

use Assada\Achievements\Achievement;

class UserUploaded800Subtitles extends Achievement
{
    /**
     * The achievement name.
     *
     * @var string
     */
    public $name = 'UserUploaded800Subtitles';

    /**
     * A small description for the achievement.
     *
     * @var string
     */
    public $description = 'You have made 800 subtitle uploads!';

    /**
     * The amount of "points" this user need to obtain in order to complete this achievement.
     *
     * @var int
     */
    public $points = 800;
}
