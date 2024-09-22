<?php

declare(strict_types=1);
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

return [
    /*
    |--------------------------------------------------------------------------
    | Donation System
    |--------------------------------------------------------------------------
    |
    | Configure site to use Donation System
    |
    */
    'is_enabled'  => false,
    'goal'        => 1200, // General goal, used for either monthly or yearly based on goal_type
    'goal_type'   => 'monthly', // 'monthly' or 'yearly'
    'start_date'  => '2024-09-20', // Donation drive start date
    'currency'    => 'USD',
    'description' => 'Help keep the site alive by donating to our goal.',
];
