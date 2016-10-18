<?php

use Wizdraw\Models\Group;

class GroupsTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::truncate();

        factory(Group::class, 10)->create();
    }
}
