<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Goal
    |--------------------------------------------------------------------------
    |
    | Yearly Goal
    |
    */
    'goal' => '2400',

    /*
    |--------------------------------------------------------------------------
    | Stripe
    |--------------------------------------------------------------------------
    |
    | Stripe Settings
    |
    */
    'stripe' => [
        'enable' => env('STRIPE_ENABLE', true),
        'model' => \App\User::class,
        'key' => '',
        'secret' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Deals
    |--------------------------------------------------------------------------
    |
    | Here are your package deals (PRICE IN CENTS!)(TIME IN DAYS!)
    |
    */
    'packages' => [
    'one' => [
        'name' => 'Show Some Love',
        'image' => '/img/package1.png',
        'price' => '500',
        'time' => '14',
    ],
    'two' => [
        'name' => 'Team Love',
        'image' => '/img/package2.jpg',
        'price' => '1000',
        'time' => '42',
    ],
    'three' => [
        'name' => 'Hulk Smash',
        'image' => '/img/package3.jpg',
        'price' => '2500',
        'time' => '92',
    ],
    'four' => [
        'name' => 'Power Up',
        'image' => '/img/package4.jpg',
        'price' => '5000',
        'time' => '275',
    ],
    ],
];
