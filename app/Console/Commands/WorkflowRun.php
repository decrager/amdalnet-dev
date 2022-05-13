<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WorkflowRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workflow transition runner for daily schedule';

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
     * @return int
     */
    public function handle()
    {
        LOG::info("Workflow run, do workflow process schedule daily");
        return 0;
    }
}
