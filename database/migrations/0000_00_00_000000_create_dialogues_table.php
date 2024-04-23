<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDialoguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialogues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('create_user_id')->default(0)->comment("创建人，关联用户表users的ID，如果是系统则为0");
            $table->integer('member_count')->default(0)->comment('成员数量');
            $table->string('type')->default('')->comment('类型:single-单一对话，group-群聊');
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dialogues');
    }
}
