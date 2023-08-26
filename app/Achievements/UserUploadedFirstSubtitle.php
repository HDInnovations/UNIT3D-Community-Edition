<?php

namespace App\Achievements;

use Assada\Achievements\Achievement;
use Assada\Achievements\Model\AchievementProgress;

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

    //    /**
    //     * A small description for the award.
    //     *
    //     * @var string
    //     */
    //    public $award = "1000 Bonus Points";

    //    /**
    //     * Triggers whenever an Achiever unlocks this achievement.
    //     *
    //     * @param  AchievementProgress $progress
    //     * @return void
    //     */
    //    public function whenUnlocked($progress)
    //    {
    //        $achiever = $progress->achiever;
    //
    //        $user = User::findOrFail($achiever->id);
    //        $user->seedbonus += 1000;
    //        $user->save();
    //    }
}
