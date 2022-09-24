<?php

namespace App\Jobs;

use App\Models\FailedRequest;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendRequestJob implements ShouldQueue
{
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

 // Question 4 : I implemented this property for the maximum number of retries for the request
 public $tries = 100;
 public $url   = '';
 /**
  * Create a new job instance.
  *
  * @return void
  */
 public function __construct($url)
 {
  $this->url = $url;
 }

 /**
  * Execute the job.
  *
  * @return void
  */
 public function handle()
 {
  try {
   // Question 5: I implemented a Queue feature for all the request sent to the job, it has a DB log on the jobs table
   $response = Http::post($this->url);
   if ($response->status() != 200) {
    throw new Exception("There's an error");
   }
  } catch (Exception $e) {
   print($e->getMessage());
   // Question 4 and 5: I added a Log for to have the reference of how many attempts failed and send a warning if it needs to notify somebody.
   Log::critical('The request is retrying.');
   /* Question 4 : I implemented this release method that it cames from the ShouldQueue interface to retries the number declared on the top of the Job and
   I sent the time between the attempts as parameter in this case 5 seconds but we could set whatever we want.*/
   $this->release(5);

   // Question 5: For the failed attempts I created a log table for those specific request to have as reference
   if ($this->attempts() >= $this->tries) {
    FailedRequest::create([
     'uri'      => $this->url,
     'attempts' => $this->attempts(),
    ]);
   }

  }
 }
 //  Question 4 :If it needs to run unitl a certain time we use the function below.
 /**
  * Determine the time at which the job should timeout.
  *
  * @return \DateTime
  */
//  public function retryUntil()
 //  {

//    return now()->addMinutes(10);
 //   }
}
