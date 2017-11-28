<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role1 = new Role();
        $role1->name = '总经理';
        $role1->level = 1;

        $role1->save();

        $role2 = new Role();
        $role2->name = '经理';
        $role2->level = 2;

        $role2->save();
    }
}
