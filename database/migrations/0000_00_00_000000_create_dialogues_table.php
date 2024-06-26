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
            $table->integer('member_count')->default(0)->comment('成员数量');
            $table->string('channel')->default('normal')->comment('对话频道:system-系统消息，normal-普通消息');
            $table->integer('create_user_id')->default(0)->comment("创建人，关联用户表users的ID，如果是系统则为0");
            $table->integer('delete_user_id')->default(0)->comment('删除人ID');
            $table->dateTime('last_message_created_at')->comment('最新一条信息的时间');
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
