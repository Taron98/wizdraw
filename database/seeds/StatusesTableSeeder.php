<?php

use Wizdraw\Models\Status;

/**
 * Class StatusesTableSeeder
 */
class StatusesTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();

        $this->createByConsts(Status::class, 'status');
    }
}
