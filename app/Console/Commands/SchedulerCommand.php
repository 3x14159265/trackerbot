<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Bot\CommandFactory;

class SchedulerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs all Bot scheduler commands.';

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
        $commands = CommandFactory::$commands;

        foreach($commands as $command) {
            (new $command)->schedule();
        }
    }
}
