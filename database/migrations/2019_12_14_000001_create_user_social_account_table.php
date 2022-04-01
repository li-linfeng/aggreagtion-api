<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSocialAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_account', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment("关联的用户id");
            $table->string('uid')->default("")->comment("第三方账号uid,唯一标识");
            $table->string('nickname')->default("")->comment('第三方账号昵称');
            $table->string('name')->default("")->comment('第三方账号真实用户名,可以为空');
            $table->string('avatar')->nullable()->comment("第三方账号头像");
            $table->string('social', 30)->default("")->comment("第三方账号类型,比如 微信，apple,facebook等");
            $table->string('email')->default("")->comment("第三方账号邮箱");
            $table->timestamps();
            $table->unique(['social', 'uid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_social_account');
    }
}
