<?php

use Wizdraw\Models\Status;

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
