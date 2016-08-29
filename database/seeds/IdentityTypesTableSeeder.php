<?php

use Illuminate\Database\Seeder;
use Wizdraw\Models\IdentityType;

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

        $identityTypes = [
            [
                'type' => 'Passport',
            ],
            [
                'type' => 'ID Card',
            ],
            [
                'type' => 'Driver License',
            ],
        ];

        IdentityType::insert($identityTypes);
    }
}
