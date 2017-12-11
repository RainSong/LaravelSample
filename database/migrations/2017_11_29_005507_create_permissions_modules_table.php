<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permissions_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('permissions_id')->nullable(false)->comment('权限ID');
            $table->integer('module_id')->nullable(false)->default(0)->comment('模块ID');
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
        Schema::dropIfExists('permissions_modules');
    }
}
