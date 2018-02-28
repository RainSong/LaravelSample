<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            //
            $table->increments("id");
            $table->integer("department_id")->nullable(false)->default(0)->comment("部門ID");
            $table->datetime("date")->nullable(false)->comment("上報日期");
            $table->integer("total")->nullable(false)->defalt()->comment("應到人數");
            $table->integer("attendance")->nullable(false)->default(0)->comment("實到人數");
            $table->integer("leave")->nullable(false)->default(0)->comment("請假人數");
            $table->integer("other")->nullable(false)->default(0)->comment("其他人數");
            $table->integer("report_user_id")->nullable(false)->default(0)->comment("上報人id");
            $table->integer('state')->nullable(false)->default(0)->comment("状态 0 正常 1 删除");
            $table->string('remark',500)->nullable(true)->comment('备注');
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
        Schema::dropIfExists('attendances');
    }
}
