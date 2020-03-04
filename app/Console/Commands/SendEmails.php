<?php

namespace App\Console\Commands;

use App\Jobs\EmailSend;
use App\Mail\UserRegistered;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the test email to the user mentioned in parameter.';

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
        EmailSend::dispatch($this->argument('user'), new UserRegistered())->delay(now()->addSeconds(10));
        $this->info("Email sent to ".$this->argument('user')." successfully!");
    }
}
