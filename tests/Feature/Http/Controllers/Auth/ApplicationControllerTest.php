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

use App\Models\Application;

test('create returns an ok response', function (): void {
    $response = $this->get(route('application.create'));
    $response->assertOk();
    $response->assertViewIs('auth.application.create');
});

test('store returns an ok response', function (): void {
    config(['captcha.enabled' => false]);
    config(['other.application_signups' => true]);

    $application = Application::factory()->make();

    $response = $this->post(route('application.store'), [
        'application' => [
            'type'     => $application->type,
            'email'    => $application->email,
            'referrer' => $application->referrer,
        ],
        'images' => [
            [
                'image' => 'https://example.org/1',
            ],
            [
                'image' => 'https://example.org/2',
            ],
        ],
        'links' => [
            [
                'url' => 'https://example.org/1',
            ],
            [
                'url' => 'https://example.org/2',
            ],
        ]
    ]);
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success', trans('auth.application-submitted'));
});
