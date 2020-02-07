<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateHelpers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {helper_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new helper class';

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
        if (!File::isDirectory(app_path('Helpers'))) {
            File::makeDirectory(app_path('Helpers'), 0777, true, true);
        }
        $helperFile = app_path('Helpers') . '/' . $this->argument('helper_name') . '.php';
        if (!File::exists($helperFile)) {
            File::makeDirectory($helperFile, 0777, true, true);
            $this->info("Helper created successfully!");
        } else {
            $this->error("Helper already exists!");
        }
    }
}
