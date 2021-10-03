<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Workflow transition runner';

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
        echo "Workflow run\n";
        return 0;
    }
}
