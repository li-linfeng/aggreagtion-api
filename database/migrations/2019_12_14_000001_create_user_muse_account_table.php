<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUserMuseAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment("关联的用户id");
            $table->string('ip')->default('')->comment("登录ip");
            $table->string('imei')->default('')->comment("手机唯一设备码");
            $table->string('client')->default('')->comment("登录客户端");
            $table->timestamps();
        });
        DB::statement("alter table `user_login_log` comment '登录日志'");


        Schema::create('user_muse_account', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment("关联的用户id");
            $table->timestamp('start_time')->nullable("")->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment("结束时间");
            $table->timestamps();
        });
        DB::statement("alter table `user_muse_account` comment '冥想账户表'");


        //类似于存款记录
        Schema::create('user_muse_account_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment("关联的用户id");
            $table->string('type')->default('')->comment("账户增加时常的途径，比如share,first_login,purchase");
            $table->string('account_id')->default("")->comment('账户id');
            $table->integer('add_time')->nullable("")->comment('增加的时长,单位：天');
            $table->integer('admin_id')->default(0)->comment('操作的管理员的id');
            $table->timestamps();
        });

        DB::statement("alter table `user_muse_account` comment '增加冥想时长的记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_muse_account');
        Schema::dropIfExists('user_muse_account_log');
        Schema::dropIfExists('user_login_log');
    }
}
