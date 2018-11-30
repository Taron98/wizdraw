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
                'color'           => '',
            ],
            [
                'status'          => TransferStatus::STATUS_POSTED,
                'original_status' => 'WAIT',
                'color'           => '#50b9f1',
            ],
            [
                'status'          => TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN,
                'original_status' => 'WAIT FOR PROCESS(Obligo)',
                'color'           => '#f7a54f',
            ],
            [
                'status'          => TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_CIRCLE_K,
                'original_status' => 'WAIT FOR PROCESS(Obligo)',
                'color'           => '#f7a54f',
            ],
            [
                'status'          => TransferStatus::STATUS_WAIT_FOR_PROCESS,
                'original_status' => TransferStatus::STATUS_WAIT_FOR_PROCESS,
                'color'           => '',
            ],
            [
                'status'          => TransferStatus::STATUS_ON_HOLD,
                'original_status' => 'WAIT FOR PROCESS(Compliance)',
                'color'           => '#f66360',
            ],
            [
                'status'          => TransferStatus::STATUS_CHECK_DOCUMENTS,
                'original_status' => TransferStatus::STATUS_CHECK_DOCUMENTS,
                'color'           => '',
            ],
            [
                'status'          => TransferStatus::STATUS_PENDING,
                'original_status' => TransferStatus::STATUS_PENDING,
                'color'           => '',
            ],
            [
                'status'          => TransferStatus::STATUS_AWAITING_WITHDRAWAL,
                'original_status' => 'POSTED',
                'color'           => '#f0dd26',
            ],
            [
                'status'          => TransferStatus::STATUS_CANCELLED,
                'original_status' => 'FAILED',
                'color'           => '#7e7e7e',
            ],
            [
                'status'          => TransferStatus::STATUS_REQUEST_AMENDMENT,
                'original_status' => 'CHANGE',
                'color'           => '#d190e0',
            ],
            [
                'status'          => TransferStatus::STATUS_REQUEST_CANCEL,
                'original_status' => TransferStatus::STATUS_REQUEST_CANCEL,
                'color'           => '',
            ],
            [
                'status'          => TransferStatus::STATUS_FOR_VERIFICATION,
                'original_status' => TransferStatus::STATUS_FOR_VERIFICATION,
                'color'           => '',
            ],
            [
                'status'          => TransferStatus::STATUS_COMPLETED,
                'original_status' => 'CONFIRMED',
                'color'           => '#6fca56',
            ],
            [
                'status'          => TransferStatus::STATUS_PREPAID_POSTED,
                'original_status' => TransferStatus::STATUS_PREPAID_POSTED,
                'color'           => '',
            ],
        ];

        TransferStatus::insert($transferStatuses);
    }
}
