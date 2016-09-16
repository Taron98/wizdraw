<?php

$factory->define(Wizdraw\Models\GroupMember::class, function (Faker\Generator $faker) {
    return [
        'group_id' => $faker->randomElement(Wizdraw\Models\Group::pluck('id')->unique()->toArray()),
        'client_id' => $faker->randomElement(Wizdraw\Models\Client::pluck('id')->unique()->toArray()),
        'is_approved' => $faker->boolean
    ];
});