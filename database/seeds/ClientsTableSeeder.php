<?php

use Illuminate\Database\Seeder;
use Wizdraw\Models\Client;

/**
 * Class ClientsTableSeeder
 */
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

        factory(Client::class, 10)->create();
    }
}
