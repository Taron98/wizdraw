<?php

use Wizdraw\Models\Nature;
use Wizdraw\Models\Status;

class NaturesTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();

        $this->createByConsts(Nature::class, 'nature');
    }
}
