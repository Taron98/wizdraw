<?php

$factory->define(Wizdraw\Models\Group::class, function (Faker\Generator $faker) {
    return [
        'name'            => $faker->lastName,
        'admin_client_id' => Wizdraw\Models\Client::all()->random()->id,
    ];
});