<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessageStatusesTable.
 */
class CreateDialogStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialog_statuses', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dialog_message_id')->default(0)->comment("外键，关联消息表的message_id。");
            $table->integer('dialog_member_id')->default(0)->comment("外键，关联用户表的user_id，表示该状态属于哪个用户。");
            $table->tinyInteger('is_hidden')->default(0)->comment('是否隐藏，0显示，1隐藏');
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
        Schema::drop('dialog_statuses');
    }
}
