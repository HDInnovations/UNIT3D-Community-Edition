<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */
 
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Torrent::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'slug' => $faker->slug,
        'description' => $faker->sentence,
        'info_hash' => $faker->md5,
        'file_name' => str_slug(strtolower($faker->name)),
        'num_file' => $faker->numberBetween(1, 50),
        'announce' => config('app.url').'/announce',
        'size' => $faker->randomFloat(1, 0.00, 4000.00),
        'category_id' => $faker->numberBetween(1, 3),
        'user_id' => 0,
        'type' => $faker->boolean(50) ? '1080p' : '720p',
        'moderated_at' => $faker->dateTime('now'),
        'moderated_by' => 1,
        'status' => $faker->boolean(80) ? 1 : 0,
        'seeders' => $faker->numberBetween(0, 50),
        'leechers' => $faker->numberBetween(0, 100)
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'email' => $faker->safeEmail,
        'password' => bcrypt('secret'),
        'passkey' => str_random(16),
        'group_id' => $faker->numberBetween(1, 18),
        'active' => $faker->boolean(70),
        'uploaded' => 53687091200,
        'downloaded' => 1073741824,
        'fl_tokens' => $faker->numberBetween(0, 30),
        'seedbonus' => $faker->randomFloat(1, 0.00, 10.00),
        'invites' => $faker->numberBetween(0,10),
        'hitandruns' => $faker->numberBetween(0,5),
        'remember_token' => str_random(10),
    ];
});