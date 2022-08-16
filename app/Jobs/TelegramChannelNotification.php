<?php

namespace App\Jobs;

use App\Helpers\HelperAdjustment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram;

class TelegramChannelNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message, $channel;

    /**
     * Create a new job instance.
     *
     * @param null $channel
     * @param string $message
     */
    public function __construct(string $message, $channel = null)
    {
        $this->channel = $channel == null ? HelperAdjustment::getTelegramNotificationChannelId() : $channel;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->channel == null) {
            Log::info('You haven\'t specified telegram notification channel.');
            return;
        }

        (new Telegram(env('TELEGRAM_BOT_TOKEN')))->sendMessage([
            'chat_id' => '@' . $this->channel,
            'text' => $this->message
        ]);
    }
}
