<?php

use Wizdraw\Models\Nature;

/**
 * Class NaturesTableSeeder
 */
class NaturesTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nature::truncate();

        $this->createByConsts(Nature::class, 'nature');
    }
}
