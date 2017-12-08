<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level')->nullable(false)->default(1)->comment('级别');
            $table->integer('parent_id')->nullable(false)->default(0)->comment('父级ID');
            $table->string('path',200)->nullable(false)->comment('路径');
            $table->string('name')->nullable(false)->comment('菜单名');
            $table->string('component', 200)->nullable(false)->comment('菜单模块');
            $table->string('display_name',50)->nullable(true)->comment('菜单名');
            $table->boolean('visible')->nullable(false)->default(true)->comment('是否显示');
            $table->integer('sort')->nullable(true)->default(0)->comment('排序');
            $table->integer('state')->nullable(false)->default(0)->comment('状态 0 正常 1 删除/禁用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
