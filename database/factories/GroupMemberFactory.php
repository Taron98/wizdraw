<?php

$factory->define(Wizdraw\Models\GroupMember::class, function (Faker\Generator $faker) {
    return [
        'group_id'         => Wizdraw\Models\Group::all()->random()->id,
        'member_client_id' => factory(\Wizdraw\Models\Client::class)->create()->id,
        'is_approved'      => $faker->boolean,
    ];
});