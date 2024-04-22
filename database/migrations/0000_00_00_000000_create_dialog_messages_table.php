<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessagesTable.
 */
class CreateDialogMessagesTable extends Migration
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
            $table->integer('dialog_id')->default(0)->comment('关联dialog，对话ID。');
            $table->integer('dialog_member_id')->default(0)->comment('关联dialog_member，消息发送者。');
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
        Schema::drop('dialog_messages');
    }
}
