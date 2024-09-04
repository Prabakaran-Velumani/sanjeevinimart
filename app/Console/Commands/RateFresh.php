<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RateFresh extends Command
{
    protected $signature = 'rate:fresh {--force}';
    protected $description = 'Fresh the rate data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Command logic here
    }
}
