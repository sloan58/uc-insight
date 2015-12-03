<?php

namespace App\Jobs;

use Aloha\Twilio\Twilio;
use App\CDR;
use App\Exceptions\TwilioException;
use App\Jobs\Job;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class PlaceTwilioCall extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $twilio;
    protected $callList;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($callList)
    {
        $this->twilio = new Twilio(env('TWILIO_SID'),env('TWILIO_TOKEN'),env('TWILIO_FROM'));
        $this->callList = $callList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->callList as $call)
        {

            $number = $call[0];
            $e164 = '+1' . $number;
            $say = $call[1];

            $cdr = new CDR();
            $cdr->dialednumber = $e164;
            $cdr->callerid = env('TWILIO_FROM');
            $cdr->calltype = 'Phone Call';
            $cdr->message = $say;

            if(strlen($number) != 10)
            {

                $cdr->successful = false;
                $cdr->failurereason = "The Dailed Number " . $number . " is not 10 digits in length";
                $cdr->save();

                Log::debug("The Dailed Number " . $number . " is not 10 digits in length", ['cdr' => $cdr->id]);
                Log::debug(['callerid' => env('TWILIO_FROM')]);

            } else {

                try {

                    $this->twilio->call($e164, 'http://twimlets.com/message?Message%5B0%5D=' . urlencode($say));
//                    $this->twilio->message($e164, $say);

                    $cdr->successful = true;
                    $cdr->save();

                } catch(Exception $e) {

                    Throw new TwilioException($e->getMessage());

                }

            }

            sleep(2);

        }
    }
}
