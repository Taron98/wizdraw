<?php

use Illuminate\Database\Seeder;
use Wizdraw\Models\TransferStatus;

class AddTransferStatusWaitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transferStatuses = [
            [
                'status' => TransferStatus::STATUS_WAIT,
                'original_status' => 'PENDING',
                'color' => '#50b9f1',
            ],
        ];
        TransferStatus::insert($transferStatuses);
    }
}
