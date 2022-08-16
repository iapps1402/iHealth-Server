<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\FoodSuggestionNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class FoodSuggestionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:food_suggestion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        User::chunk(200, function ($users) {
            foreach ($users as $user) {
                if ($user->has_food_suggestion)
                    Notification::send($user, new FoodSuggestionNotification());
            }
        });
    }
}
