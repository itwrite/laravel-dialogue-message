<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessageStatusesTable.
 */
class CreateDialogMessageStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialog_message_statuses', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dialog_message_id')->default(0)->comment("外键，关联消息表的message_id。");
            $table->integer('dialog_member_id')->default(0)->comment("外键，关联用户表的user_id，表示该状态属于哪个用户。");
            $table->tinyInteger('is_read')->default(0)->comment('是否已读标记，0未读，1已读');
            $table->tinyInteger('is_removed')->default(0)->comment('是否已删除，0未删，1已删');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('消息状态表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dialog_message_statuses');
    }
}
