<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessagesTable.
 */
class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialog_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->default(0)->comment('关联用户表的user_id，表示消息发送者。');
            $table->integer('receiver_id')->default(0)->comment('关联用户表的user_id，表示消息接收者。');
            $table->text("content")->nullable(true)->comment('消息正文');
            $table->string('type')->default('')->comment('消息类型');
            $table->timestamps();
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
        Schema::drop('messages');
    }
}
