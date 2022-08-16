<?php

namespace App\Console\Commands;

use App\Jobs\TelegramChannelNotification;
use App\Models\User;
use Illuminate\Console\Command;

class AdminDietProgramReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:admin_diet_program_reminder';

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
        User::whereNotNull('diet_program_period')
            ->where('customer', 1)
            ->whereHas('dietPrograms')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    $lastProgram = $user->dietPrograms()->orderByDesc('created_at')->first();
                    if ($lastProgram == null || $lastProgram->created_at->addDays($user->diet_program_period - 1) > now() ||
                        ($user->nutrition_program_notified_at != null && now()->addDays(-1) < $user->nutrition_program_notified_at)
                    )
                        return;

                    dispatch(new TelegramChannelNotification('کاربر شماره #' . $user->id . ' با شماره / ایمیل' . $user->contact . ' نیاز به برنامه جدید تغذیه دارد.'));
                }
            });
    }
}
