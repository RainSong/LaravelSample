<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $employee = new App\Models\Menu();
        $employee -> level = 1;
        $employee -> parent_id = 0;
        $employee -> path = '/Employee/Interview';
        $employee -> name = 'Employee';
        $employee -> component = 'Interview';
        $employee -> display_name = '员工管理';
        $employee -> visible = true;
        $employee -> sort = 100;

        $employee->save();

        $interview = new App\Models\Menu();
        $interview -> level = 2;
        $interview -> parent_id = $employee -> id;
        $interview -> path = '/Employee/Interview';
        $interview -> name = 'Interview';
        $interview -> component = 'Interview';
        $interview -> display_name = '面试管理';
        $interview -> visible = true;
        $interview -> sort = 100100;

        $interview -> save();

        $system = new App\Models\Menu();
        $system -> level = 1;
        $system -> parent_id = 0;
        $system -> path = '/System/Menu';
        $system -> name = 'System';
        $system -> component = 'Menu';
        $system -> display_name = '系统设置';
        $system -> visible = true;
        $system -> sort = 200;

        $system -> save();

        $menu = new App\Models\Menu();
        $menu -> level = 2;
        $menu -> parent_id = $system -> id;
        $menu -> path = '/System/Menu';
        $menu -> name = 'System';
        $menu -> component = 'Menu';
        $menu -> display_name = '菜单管理';
        $menu -> visible = true;
        $menu -> sort = 200100;

        $menu -> save();
    }
}
