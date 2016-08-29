<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Wizdraw\Models\User;

/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $users = [
            [
                'client_id'      => 1,
                'username'       => 'test',
                'password'       => Hash::make('test'),
                'facebook_token' => '',
                'device_id'      => '123e4567-e89b-12d3-a456-426655440000',
                'is_pending'     => false,
                'last_login_at'  => Carbon::now(),
            ],
        ];

        User::insert($users);
    }
}
