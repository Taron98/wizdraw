<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
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
        if ($this->isMysql()) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        }

        $this->call(IdentityTypesTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(NaturesTableSeeder::class);

        // Enable foreign keys constraints
        if ($this->isMysql()) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        }
    }

    /**
     * Check if the current database driver is mysql
     *
     * @return bool
     */
    private function isMysql() : bool
    {
        $default = config('database.default');
        $driver = config('database.connections.' . $default . '.driver');

        return strtolower($driver) === 'mysql';
    }
}
