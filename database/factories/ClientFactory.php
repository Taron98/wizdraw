<?php

$plFaker = Faker\Factory::create("pl_PL");
$brFaker = Faker\Factory::create("pt_BR");

$factory->define(Wizdraw\Models\Client::class, function (Faker\Generator $faker) use ($plFaker, $brFaker) {
    $createdAt = $faker->dateTimeThisMonth;

    return [
        'identity_type_id'    => Wizdraw\Models\IdentityType::all()->random()->id,
        'identity_number'     => $plFaker->unique()->personalIdentityNumber,
        'identity_expire'     => $faker->dateTimeBetween('now', '+5 years'),
        'first_name'          => $faker->firstName,
        'middle_name'         => $faker->optional()->firstName,
        'last_name'           => $faker->lastName,
        'birth_date'          => $faker->dateTimeThisCentury,
        'gender'              => $faker->randomElement(['male', 'female']),
        'phone'               => "+1" . $brFaker->phoneNumberCleared,
        'default_country_id'  => $faker->randomNumber(),    // todo: update after using cache tables
        'resident_country_id' => $faker->randomNumber(),    // todo: update after using cache tables
        'city'                => $faker->city,
        'birth_place'         => $faker->country,
        'date_of_issue'       => $faker->dateTimeThisCentury,
        'address'             => $faker->streetAddress,
        'client_type'         => $faker->randomElement(['sender', 'receiver']),
        'did_setup'           => $faker->boolean,
        'created_at'          => $createdAt,
        'updated_at'          => $faker->dateTimeBetween($createdAt),
    ];
});