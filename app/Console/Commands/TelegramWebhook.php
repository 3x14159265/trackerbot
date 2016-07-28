<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the telegram webhook';

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
     * @return mixed
     */
    public function handle()
    {
        $webhook = getenv('TELEGRAM_WEBHOOK').getenv('TELEGRAM_WEBHOOK_TOKEN');
        $token = getenv('TELEGRAM_TOKEN');
        $result = file_get_contents("https://api.telegram.org/bot$token/setWebhook?url=$webhook");
        $result = json_decode($result, true);
        $result = json_encode($result, JSON_PRETTY_PRINT);
        $this->info($result);
    }
}
