<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class imagescraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bot = new \App\Http\Controllers\Api\scrapDB();
        //echo $bot->testVideo();
        echo $bot->downImage();
    }
}
