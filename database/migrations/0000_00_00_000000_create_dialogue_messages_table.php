<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessagesTable.
 */
class CreateDialogueMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialogue_messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dialogue_id')->default(0)->comment('关联dialog，对话ID。');
            $table->integer('dialogue_member_id')->default(0)->comment('关联dialogue_member，消息发送者。');
            $table->text("content")->nullable(true)->comment('消息正文');
            $table->string('type')->default('text')->comment('消息类型');
            $table->timestamps();
            $table->softDeletes();
            $table->index('dialogue_id');
            $table->index('dialogue_member_id');
            $table->index(['dialogue_member_id','dialogue_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dialogue_messages');
    }
}
