<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:command {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Bot command';

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
        $command = $this->argument('name');
        $content = file_get_contents(__DIR__.'/../Stubs/Command.stub');
        $data = str_replace('<Command>', $command, $content);
        file_put_contents(__DIR__."/../../Http/Bot/$command.php", $data);
        $this->info("$command has been created.");
    }
}
