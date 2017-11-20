<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('interviews', function (Blueprint $table) {
            //
            $table->bigIncrements('id');
            $table->string('id_card', 18)->unique()->comment('身份证号码');
            $table->string('name')->nullable(false)->comment('姓名');
            $table->integer('sex')->default(1)->comment('性别 0 女 1 男');
            $table->dateTime('brithday')->comment('生日');
            $table->string('mobile', 20)->comment('手机号码');
            $table->string('address', 200)->nullable(true)->comment('住址');
            $table->datetime('entry_time')->nullable(true)->comment('拟入职时间');
            $table->datetime('interview_time')->nulable(true)->default(Carbon::now())->comment('面试时间');
            $table->string('id_card_img1', 300)->nullable(true)->comment('身份证正面照');
            $table->string('id_card_img2', 300)->nullable(true)->comment('身份证背面照');
            $table->string('remark', 500)->nullable(true)->comment('备注');
            $table->integer('state')->default(0)->comment('状态 0 正常 1 已删除');
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
        Schema::dropIfExists("interviews");
    }
}
