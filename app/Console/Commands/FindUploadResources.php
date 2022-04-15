<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FindUploadResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:findNewResource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'findNewResource';

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
        app('log')->info('执行更新任务');
    }
}
