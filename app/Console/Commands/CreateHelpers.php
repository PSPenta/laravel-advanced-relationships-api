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
    protected $signature = 'make:helper {helper_name: The name of Helper file and class}';

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
        $fileName = $this->argument('helper_name');
        if (strpos($this->argument('helper_name'), '/')) {
            $dirName = explode('/', $this->argument('helper_name'));
            $fileName = array_pop($dirName);
            $dirName = implode('/', $dirName);
            if (!File::isDirectory(app_path('Helpers').'/'.$dirName)) {
                File::makeDirectory(app_path('Helpers').'/'.$dirName, 0777, true, true);
            }
        }
        $helperFile = app_path('Helpers') . '/' . $this->argument('helper_name') . '.php';
        if (!File::exists($helperFile)) {
            $file = fopen($helperFile, 'w');
            $nameSpacePath = (strpos($this->argument('helper_name'), '/')) ? '\\'.str_replace('/', '\\', $dirName) : '';
            fwrite($file, "<?php\n\nnamespace App\Helpers".$nameSpacePath.";\n\nclass ".$fileName."\n{\n    //\n}\n");
            fclose($file);
            $this->info("Helper created successfully!");
        } else {
            $this->error("Helper already exists!");
        }
    }
}
