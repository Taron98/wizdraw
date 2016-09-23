<?php

$factory->define(Wizdraw\Models\IdentityType::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->unique()->randomElement(['Passport', 'ID Card', 'Driver License']),
    ];
});