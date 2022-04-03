<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->default('')->comment('资源名称');
            $table->string('link')->default('')->comment('资源地址');
            $table->text('description')->nullable()->comment('描述');
            $table->integer('category_id')->default(0)->comment('所属分类');
            $table->tinyInteger('is_show')->default(1)->comment('是否展示');
            $table->integer('user_id')->default(0)->comment('所属用户id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
