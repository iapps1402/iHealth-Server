<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use phplusir\smsir\Smsir;

class SendUltraFastSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $number,$parameters,$templateId;

    /**
     * Create a new job instance.
     *
     * @param array $parameters
     * @param int $templateId
     * @param $number
     */
    public function __construct(array $parameters,int $templateId,$number)
    {
        $this->parameters = $parameters;
        $this->templateId = $templateId;
        $this->number = $number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Smsir::ultraFastSend($this->parameters,$this->templateId,$this->number);
    }
}
