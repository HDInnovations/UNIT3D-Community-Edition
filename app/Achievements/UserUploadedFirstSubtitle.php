<?php

namespace App\Achievements;

use Assada\Achievements\Achievement;

class UserUploadedFirstSubtitle extends Achievement
{
    /**
     * The achievement name.
     *
     * @var string
     */
    public $name = 'UserUploadedFirstSubtitle';

    /**
     * A small description for the achievement.
     *
     * @var string
     */
    public $description = 'Congratulations! You have made your first subtitle upload!';
}
