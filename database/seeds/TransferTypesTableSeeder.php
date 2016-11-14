<?php

use Wizdraw\Models\TransferType;

/**
 * Class TransferTypesTableSeeder
 */
class TransferTypesTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferType::truncate();

        $this->createByConsts(TransferType::class, 'type');
    }
}
