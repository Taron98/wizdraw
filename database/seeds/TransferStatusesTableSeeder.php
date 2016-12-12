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
                'status'          => TransferStatus::STATUS_ABORTED,
                'original_status' => TransferStatus::STATUS_ABORTED,
            ],
            [
                'status'          => TransferStatus::STATUS_WAIT,
                'original_status' => TransferStatus::STATUS_WAIT,
            ],
            [
                'status'          => TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN,
                'original_status' => 'WAIT FOR PROCESS(Obligo)',
            ],
            [
                'status'          => TransferStatus::STATUS_WAIT_FOR_PROCESS,
                'original_status' => TransferStatus::STATUS_WAIT_FOR_PROCESS,
            ],
            [
                'status'          => TransferStatus::STATUS_WAIT_FOR_PROCESS_COMPLIANCE,
                'original_status' => TransferStatus::STATUS_WAIT_FOR_PROCESS_COMPLIANCE,
            ],
            [
                'status'          => TransferStatus::STATUS_CHECK_DOCUMENTS,
                'original_status' => TransferStatus::STATUS_CHECK_DOCUMENTS,
            ],
            [
                'status'          => TransferStatus::STATUS_PENDING,
                'original_status' => TransferStatus::STATUS_PENDING,
            ],
            [
                'status'          => TransferStatus::STATUS_AWAITING_WITHDRAWAL,
                'original_status' => 'POSTED',
            ],
            [
                'status'          => TransferStatus::STATUS_CANCELLED,
                'original_status' => 'FAILED',
            ],
            [
                'status'          => TransferStatus::STATUS_CHANGE,
                'original_status' => TransferStatus::STATUS_CHANGE,
            ],
            [
                'status'          => TransferStatus::STATUS_REQUEST_CANCEL,
                'original_status' => TransferStatus::STATUS_REQUEST_CANCEL,
            ],
            [
                'status'          => TransferStatus::STATUS_FOR_VERIFICATION,
                'original_status' => TransferStatus::STATUS_FOR_VERIFICATION,
            ],
            [
                'status'          => TransferStatus::STATUS_COMPLETED,
                'original_status' => 'CONFIRMED',
            ],
        ];

        TransferStatus::insert($transferStatuses);
    }
}
