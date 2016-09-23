<?php

use Illuminate\Database\Seeder;
use Wizdraw\Models\IdentityType;

/**
 * Class IdentityTypesTableSeeder
 */
class IdentityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IdentityType::truncate();

        factory(IdentityType::class, 3)->create();
    }
}
