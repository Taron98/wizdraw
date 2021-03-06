<?php

use Carbon\Carbon;
use Wizdraw\Models\User;

/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        factory(User::class, 10)->create();

        $this->insertDummyUser();
    }

    /**
     * Insert another user, so we can have a user for testing
     */
    private function insertDummyUser()
    {
        $users = [
            [
                'client_id'     => 1,
                'email'         => 'test@test.com',
                'password'      => Hash::make('test'),
                'facebook_id'   => '229519970783238',
                'device_id'     => '123e4567-e89b-12d3-a456-426655440000',
                'is_pending'    => false,
                'last_login_at' => Carbon::now(),
            ],
        ];

        User::insert($users);
    }
}
