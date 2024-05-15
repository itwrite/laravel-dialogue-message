<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessageStatusesTable.
 */
class CreateDialogueStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialogue_statuses', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dialogue_id')->default(0)->comment("外键，关联dialog表的dialogue_id。");
            $table->integer('dialogue_member_id')->default(0)->comment("外键，关联用户表的user_id，表示该状态属于哪个用户。");
            $table->tinyInteger('is_hidden')->default(0)->comment('是否隐藏，0显示，1隐藏');
            $table->timestamps();
            $table->softDeletes();
            $table->index('dialogue_id');
            $table->index('dialogue_member_id');
            $table->index(['dialogue_id','dialogue_member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dialogue_statuses');
    }
}
