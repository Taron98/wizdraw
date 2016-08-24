<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        // Disable foreign keys constraints
        // Required if we want to truncate (clear) tables that has an fk
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        $this->call(IdentityTypesTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        // Enable foreign keys constraints
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
