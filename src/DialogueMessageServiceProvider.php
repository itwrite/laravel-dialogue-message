<?php

namespace Itwri\DialogueMessageService;

use Illuminate\Support\ServiceProvider;

class DialogueMessageServiceProvider extends ServiceProvider
{
    public function boot(){
        $date = '2024_04_23';
        $this->publishes([
            __DIR__.'/../config/dialogue.php' => base_path('config/dialogue.php'),
        ], 'config');
        $this->publishes([
            __DIR__.'/../database/migrations/'.$date.'_000000_create_dialogues_table.php' => database_path('migrations/'.$date.'_000000_create_dialogues_table.php'),
            __DIR__.'/../database/migrations/'.$date.'_000000_create_dialog_statuses_table.php' => database_path('migrations/'.$date.'_000000_create_dialog_statuses_table.php'),
            __DIR__.'/../database/migrations/'.$date.'_000000_create_dialog_members_table.php' => database_path('migrations/'.$date.'_000000_create_dialog_members_table.php'),
            __DIR__.'/../database/migrations/'.$date.'_000000_create_dialog_messages_table.php' => database_path('migrations/'.$date.'_000000_create_dialog_messages_table.php'),
            __DIR__.'/../database/migrations/'.$date.'_000000_create_dialog_message_statuses_table.php' => database_path('migrations/'.$date.'_000000_create_dialog_message_statuses_table.php'),
        ], 'migrations');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dialogue.php', 'dialogue');
    }
}
