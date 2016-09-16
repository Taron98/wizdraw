<?php

$factory->define(Wizdraw\Models\Group::class, function (Faker\Generator $faker) {
    return [
        'name'            => $faker->firstName,
        'client_id' => $faker->randomElement(Wizdraw\Models\Client::pluck('id')->toArray()),
    ];
});