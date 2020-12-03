<?php

namespace App\Console\Commands;

use App\Services\GroupService;
use Illuminate\Console\Command;

class CalculateGroupsState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:group {--subject_id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(GroupService $groupService)
    {
        $subject_id = $this->option('subject_id');
        $groupService->updateSubjectGroupsState($subject_id);
        $this->info('done');
    }
}
