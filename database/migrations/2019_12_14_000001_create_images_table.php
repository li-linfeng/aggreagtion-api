<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('source_id')->default(0)->comment("图片关联的资源id");
            $table->string('source_type')->default("")->comment("图片关联的资源类型,目前只有用户头像");
            $table->string('path')->default("")->comment("图片存储路径");
            $table->string('url')->default("")->comment("图片完整url");
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
        Schema::dropIfExists('images');
    }
}
