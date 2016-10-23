<?php

use Wizdraw\Models\IdentityType;

/**
 * Class IdentityTypesTableSeeder
 */
class IdentityTypesTableSeeder extends AbstractTableSeeder
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
