<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDialogueMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialogue_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dialogue_id')->default(0)->comment('对话ID');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('alias_name')->default('')->comment('别名');
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('dialogue_id');
            $table->index('user_id');
            $table->index(['user_id','dialogue_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dialogue_members');
    }
}
