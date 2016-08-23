<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmailReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crossover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email report about visitor';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO: Send emails here;
        $this->comment(PHP_EOL.'Emails sent'.PHP_EOL);
    }
}
