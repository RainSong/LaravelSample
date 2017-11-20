<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('level')->nullable(false)->default(1)->comment('级别');
            $table->integer('parent_id')->nullable(true)->default(0)->comment('父级ID');
            $table->string('name',50)->nullable(false)->comment('部门名称');
            $table->integer('sort')->nullable(false)->default(0)->comment('排序');
            $table->integer('state')->nullable(false)->default(0)->comment('状态 0 正常 1 禁用 2 删除');
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
        Schema::dropIfExists('departments');
    }
}
