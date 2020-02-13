<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\ApplicationImageProof::class, function (Faker $faker) {
    return [
        'application_id' => function () {
            return factory(App\Models\Application::class)->create()->id;
        },
    ];
});
