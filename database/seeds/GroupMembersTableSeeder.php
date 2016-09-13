<?php

use Illuminate\Database\Seeder;
use Wizdraw\Models\GroupMember;

class GroupMembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupMember::truncate();

        factory(GroupMember::class, 10)->create();
    }
}
