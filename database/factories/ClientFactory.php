<?php

$factory->define(Wizdraw\Models\Client::class, function (Faker\Generator $faker) {

    $plPerson = new \Faker\Provider\pl_PL\Person($faker);
    $brPhoneNumber = new \Faker\Provider\pt_BR\PhoneNumber($faker);
    $createdAt = $faker->dateTimeThisMonth;

    return [
        'identity_type_id'    => Wizdraw\Models\IdentityType::all()->random()->id,
        'identity_number'     => $plPerson->personalIdentityNumber(),
        'identity_expire'     => $faker->dateTimeBetween('now', '+5 years'),
        'first_name'          => $faker->firstName,
        'middle_name'         => $faker->optional()->firstName,
        'last_name'           => $faker->lastName,
        'birth_date'          => $faker->dateTimeThisCentury,
        'gender'              => $faker->randomElement(['male', 'female']),
        'phone'               => "+1" . $brPhoneNumber->phoneNumberCleared(),
        'default_country_id'  => $faker->randomNumber(),    // todo: update after using cache tables
        'resident_country_id' => $faker->randomNumber(),    // todo: update after using cache tables
        'city'                => $faker->city,
        'address'             => $faker->streetAddress,
        'client_type'         => $faker->randomElement(['sender', 'receiver']),
        'did_setup'           => $faker->boolean,
        'created_at'          => $createdAt,
        'updated_at'          => $faker->dateTimeBetween($createdAt),
    ];
});