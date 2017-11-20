<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $department_guanli = new \App\Models\Deaprtment();
        $department_guanli->level = 0;
        $department_guanli->parent_id = 0;
        $department_guanli->name = '管理部';
        $department_guanli->sort = 100;
        $department_guanli->save();

        $department_caiwu = new \App\Models\Deaprtment();
        $department_caiwu->level = 1;
        $department_caiwu->parent_id = $department_guanli->id;
        $department_caiwu->name = '财务部';
        $department_caiwu->sort = 100100;
        $department_caiwu -> save();

        $department_renli = new \App\Models\Deaprtment();
        $department_renli->level = 1;
        $department_renli->parent_id = $department_guanli->id;
        $department_renli->name = '财务部';
        $department_renli->sort = 100200;
        $department_renli->save();

        $department_fawu = new \App\Models\Deaprtment();
        $department_fawu->level = 1;
        $department_fawu->parent_id = $department_guanli->id;
        $department_fawu->name = '法务部';
        $department_fawu->sort = 100300;
        $department_fawu->save();

        $department_yewu = new \App\Models\Deaprtment();
        $department_yewu->level = 0;
        $department_yewu->parent_id = 0;
        $department_yewu->name = '业务部';
        $department_yewu->sort = 200;
        $department_yewu->save();

        $department_yewukaifa = new \App\Models\Deaprtment();
        $department_yewukaifa->level = 1;
        $department_yewukaifa->parent_id = $department_yewu->id;
        $department_yewukaifa->name = '业务开发部';
        $department_yewukaifa->sort = 200100;
        $department_yewukaifa->save();

        $department_shouqian = new \App\Models\Deaprtment();
        $department_shouqian->level = 1;
        $department_shouqian->parent_id = $department_yewu->id;
        $department_shouqian->name = '售前服务部';
        $department_shouqian->sort = 200100;
        $department_shouqian->save();

        $department_shouhou = new \App\Models\Deaprtment();
        $department_shouhou->level = 1;
        $department_shouhou->parent_id = $department_yewu->id;
        $department_shouhou->name = '售后服务部';
        $department_shouhou->sort = 200300;
        $department_shouhou->save();

        $department_jishu = new \App\Models\Deaprtment();
        $department_jishu->level = 0;
        $department_jishu->parent_id = 0;
        $department_jishu->name = '技术部';
        $department_jishu->sort = 300;
        $department_jishu->save();

        $department_xiangmu = new \App\Models\Deaprtment();
        $department_xiangmu->level = 1;
        $department_xiangmu->parent_id = $department_jishu ->id;
        $department_xiangmu->name = '项目部';
        $department_xiangmu->sort = 300100;
        $department_xiangmu->save();

        $department_yingjian = new \App\Models\Deaprtment();
        $department_yingjian->level = 1;
        $department_yingjian->parent_id = $department_jishu ->id;
        $department_yingjian->name = '硬件部';
        $department_yingjian->sort = 300200;
        $department_yingjian->save();

        $department_ruanjian = new \App\Models\Deaprtment();
        $department_ruanjian->level = 1;
        $department_ruanjian->parent_id = $department_jishu ->id;
        $department_ruanjian->name = '软件部';
        $department_ruanjian->sort = 300300;
        $department_ruanjian->save();

        $department_ceshi = new \App\Models\Deaprtment();
        $department_ceshi->level = 1;
        $department_ceshi->parent_id = $department_jishu ->id;
        $department_ceshi->name = '测试部';
        $department_ceshi->sort = 300400;
        $department_ceshi->save();
    }
}
