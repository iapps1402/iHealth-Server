<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserWeight;
use App\Notifications\DontForgetToAddYourWeightNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class DontForgetToAddYourWeightCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dont_forget_to_add_your_weight';

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
        User::whereDoesntHave('userWeight', function ($q) {
            $q->whereDate('date', now());
        })->chunk(200, function ($users) {
            foreach ($users as $user)
                    Notification::send($user, new DontForgetToAddYourWeightNotification());
        });
    }
}
