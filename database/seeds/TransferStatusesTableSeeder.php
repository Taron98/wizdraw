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

        $transferStatuses = [
            [
                'status'   => TransferStatus::STATUS_ABORTED,
                'original' => TransferStatus::STATUS_ABORTED,
            ],
            [
                'status'   => TransferStatus::STATUS_WAIT,
                'original' => TransferStatus::STATUS_WAIT,
            ],
            [
                'status'   => TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN,
                'original' => 'WAIT FOR PROCESS(Obligo)',
            ],
            [
                'status'   => TransferStatus::STATUS_WAIT_FOR_PROCESS,
                'original' => TransferStatus::STATUS_WAIT_FOR_PROCESS,
            ],
            [
                'status'   => TransferStatus::STATUS_WAIT_FOR_PROCESS_COMPLIANCE,
                'original' => TransferStatus::STATUS_WAIT_FOR_PROCESS_COMPLIANCE,
            ],
            [
                'status'   => TransferStatus::STATUS_CHECK_DOCUMENTS,
                'original' => TransferStatus::STATUS_CHECK_DOCUMENTS,
            ],
            [
                'status'   => TransferStatus::STATUS_PENDING,
                'original' => TransferStatus::STATUS_PENDING,
            ],
            [
                'status'   => TransferStatus::STATUS_AWAITING_WITHDRAWAL,
                'original' => 'POSTED',
            ],
            [
                'status'   => TransferStatus::STATUS_CANCELLED,
                'original' => 'FAILED',
            ],
            [
                'status'   => TransferStatus::STATUS_CHANGE,
                'original' => TransferStatus::STATUS_CHANGE,
            ],
            [
                'status'   => TransferStatus::STATUS_REQUEST_CANCEL,
                'original' => TransferStatus::STATUS_REQUEST_CANCEL,
            ],
            [
                'status'   => TransferStatus::STATUS_FOR_VERIFICATION,
                'original' => TransferStatus::STATUS_FOR_VERIFICATION,
            ],
            [
                'status'   => TransferStatus::STATUS_COMPLETED,
                'original' => 'CONFIRMED',
            ],
        ];

        TransferStatus::insert($transferStatuses);
    }
}
