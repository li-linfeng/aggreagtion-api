<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id')->comment('用户id');
            $table->string('name')->comment('收藏夹名称');
        });

        Schema::create('user_collections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id')->comment('用户id');
            $table->integer('resource_id')->comment('源id');
            $table->integer('collection_id')->comment('收藏夹id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
        Schema::dropIfExists('user_collections');
    }
}
