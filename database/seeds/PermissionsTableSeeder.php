<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permission1 = new Permission();
        $permission1->name = '职员管理';
        $permission1->sort = 10000;
        $permission1->save();

        $permission11 = new Permission();
        $permission11->parent_id = $permission1->id;
        $permission11->name = '面试管理';
        $permission11->sort = 100100;
        $permission11->save();

        $permission2 = new Permission();
        $permission2->name = '系统设置';
        $permission2->sort = 20000;
        $permission2->save();

        $permission21 = new Permission();
        $permission21->name = '部门管理';
        $permission21->parent_id = $permission2->id;
        $permission21->sort = 200100;
        $permission21->save();

        $permission22 = new Permission();
        $permission22->name = '用户管理';
        $permission22->parent_id = $permission2->id;
        $permission22->sort = 200300;
        $permission22->save();

        $permission23 = new Permission();
        $permission23->name = '角色管理';
        $permission23->parent_id = $permission2->id;
        $permission23->sort = 200300;
        $permission23->save();

        $permission23 = new Permission();
        $permission23->name = '权限管理';
        $permission23->parent_id = $permission2->id;
        $permission23->sort = 200400;
        $permission23->save();
    }
}
