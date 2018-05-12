<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class clearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears several common cache\'s ...';

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
        $this->comment('Clearing several common cache\'s ...');
        $this->call('view:cache');
        $this->call('route:cache');
        $this->call('config:clear');
        //$this->call('cache:clear');
    }
}
