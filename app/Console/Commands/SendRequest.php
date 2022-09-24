<?php

namespace App\Console\Commands;

use App\Jobs\SendRequestJob;
use Illuminate\Console\Command;

class SendRequest extends Command
{
 /**
  * The name and signature of the console command.
  *
  * @var string
  */
 protected $signature = 'request';

 /**
  * The console command description.
  *
  * @var string
  */
 protected $description = 'Executes a request to a given URL';

 /**
  * Execute the console command.
  *
  * @return int
  */
 public function handle()
 {
  // This command needs `php artisan queue:work` to run on the background
  // Question 5 : It could be implemented a file reading or db reading and read each of the data and send the URL as a parameter
  // Excutes the SendRequestJob when the queue it's available
  SendRequestJob::dispatch('https://atomic.incfile.com/fakepost');
 }
}
