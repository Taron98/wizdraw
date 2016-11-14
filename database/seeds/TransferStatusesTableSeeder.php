<?php

use Wizdraw\Models\TransferStatus;

/**
 * Class TransferStatusesTableSeeder
 */
class TransferStatusesTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferStatus::truncate();

        $this->createByConsts(TransferStatus::class, 'status');
    }
}
