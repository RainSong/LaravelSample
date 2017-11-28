<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permissions',function (Blueprint $table){
            $table->increments('id');
            $table->integer('parent_id')->notnull(true)->default(0)->comment('父级权限ID');
            $table->string('name')->notnull(true)->comment('权限名');
            $table->integer('sort')->notnull(true)->default(0)->comment('排序');
            $table->integer('state')->notnull(true)->default(0)->comment('状态 0 正常 1 已删除');
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
        //
        Schema::dropIfExists('permissions');
    }
}
