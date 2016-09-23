<?php

use Illuminate\Database\Seeder;
use Wizdraw\Models\Group;

class GroupsTableSeeder extends Seeder
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
