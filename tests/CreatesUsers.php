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

namespace Tests;

use App\Models\Group;
use App\Models\User;

trait CreatesUsers
{
    public function asStaffUser(): static
    {
        return $this->actingAs(User::factory()->create([
            'group_id' => Group::factory()->owner(),
        ]));
    }

    public function asAuthenticatedUser(): static
    {
        return $this->actingAs(User::factory()->create());
    }
}
