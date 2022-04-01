<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code')->default('')->comment('验证码');
            $table->string('contact')->default('')->comment('联系方式,邮箱或者电话');
            $table->string('driver')->default('mobile')->comment('验证码发送方式,默认手机mobile');
            $table->string('type')->default('')->comment('验证码类型，现有register');
            $table->timestamp('expire_time')->nullable()->comment('验证码过期时间');
            $table->tinyInteger('status')->default(0)->comment('验证码状态,0未使用,1已使用');
            $table->string('error_message')->default('')->comment('验证码错误状态');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
}
