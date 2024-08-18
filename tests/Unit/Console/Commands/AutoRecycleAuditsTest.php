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

/**
 * @see App\Console\Commands\AutoRecycleAudits
 */
it('runs successfully', function (): void {
    $this->artisan('auto:recycle_audits')
        ->assertExitCode(0)
        ->run();

    // TODO: perform additional assertions to ensure the command behaved as expected
});
