<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->nullable(true)->default(0)->comment('所属部门');
            $table->string('user_name',30)->nullable(false)->comment('用户名');
            $table->string('password',50)->nullable(false)->comment('密码');
            $table->string('name',30)->nulable(false)->comment('姓名');
            $table->integer('sex')->nullable(false)->default(1)->comment(1);
            $table->dateTime('brithday')->nullable(true)->comment('生日');
            $table->string('phone',20)->nullable(true)->comment('电话');
            $table->string('email',50)->nullable(true)->comment('EMail');
            $table->string('address',300)->nullable(true)->comment('住址');
            $table->string('avater',300)->nullable(true)->comment('用户头像');
            $table->dateTime('entry_time')->nullable(true)->comment('入职时间');
            $table->integer('state')->nullable(false)->default(0)->comment('状态 0 正常 1 删除 2 离职');
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
        Schema::dropIfExists('users');
    }
}
