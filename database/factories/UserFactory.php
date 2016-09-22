<?php

$factory->define(Wizdraw\Models\User::class, function (Faker\Generator $faker) {

    return [
        'client_id'     => factory(\Wizdraw\Models\Client::class)->create()->id,
        'email'         => $faker->safeEmail,
        'password'      => $faker->password,
        'device_id'     => $faker->uuid,
        'last_login_at' => \Carbon\Carbon::now(),
        'created_at'    => \Carbon\Carbon::now(),
    ];
});