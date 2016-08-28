<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Wizdraw\Models\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::truncate();

        // TODO: Use factory

        $clients = [
            [
                'identity_type_id' => 1,
                'identity_number' => '1231543543',
                'identity_expire' => Carbon::create(2017, 05, 23),
                'first_name' => 'First',
                'middle_name' => 'Middle',
                'last_name' => 'Last',
                'birth_date' => Carbon::create(1988, 10, 8),
                'gender' => 'male',
                'phone' => '05222222222',
                'resident_country_id' => 1,
                'state' => 'Gush Dan',
                'city' => 'Tel Aviv-Yafo',
                'address' => 'Levinsky 10',
                'zip' => '1212212'
            ],
            [
                'identity_type_id' => 1,
                'identity_number' => '1231123543',
                'identity_expire' => Carbon::create(2018, 05, 23),
                'first_name' => 'First2',
                'middle_name' => 'Middle2',
                'last_name' => 'Last2',
                'birth_date' => Carbon::create(1983, 12, 3),
                'gender' => 'female',
                'phone' => '05223333333',
                'resident_country_id' => 1,
                'state' => 'Gush Dan',
                'city' => 'Petah Tikva',
                'address' => 'Herzel 2',
                'zip' => '3333333'
            ]
        ];

        Client::insert($clients);
    }
}
