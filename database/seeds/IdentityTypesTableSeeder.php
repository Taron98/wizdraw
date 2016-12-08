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

        $identityTypes = [
            [
                'type' => 'ID Card',
            ],
            [
                'type' => 'Passport',
            ],
            [
                'type' => 'Driver License',
            ]
        ];

        IdentityType::insert($identityTypes);
    }
}
