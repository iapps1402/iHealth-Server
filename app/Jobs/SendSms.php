<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use phplusir\smsir\Smsir;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number, $message;

    /**
     * Create a new job instance.
     *
     * @param string $number
     * @param string $message
     */
    public function __construct($number, $message)
    {
        $this->number = $number;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->number) && (!Str::startsWith($this->number, '091') && !Str::startsWith($this->number, '91')))
            Smsir::send($this->message, Str::startsWith($this->number, '9') ? '0' . $this->number : '' . $this->number);
    }
}
