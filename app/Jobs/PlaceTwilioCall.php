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

/**
 * Class PlaceTwilioCall
 * @package App\Jobs
 */
class PlaceTwilioCall extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var $twilio
     */
    protected $twilio;
    /**
     * @var $callList
     */
    protected $callList;

    /**
     * Create a new job instance.
     *
     * @param $callList
     */
    public function __construct($callList)
    {
        $this->twilio = new Twilio(env('TWILIO_SID'),env('TWILIO_TOKEN'),env('TWILIO_FROM'));
        $this->callList = $callList;
    }

    /**
     * Execute the job.
     *
     * @throws TwilioException
     */
    public function handle()
    {
        foreach($this->callList as $call)
        {

            $number = $call[0];
            $e164 = '+1' . $number;
            $say = $call[1];
            $type = $call[2];

            $cdr = new CDR();
            $cdr->dialednumber = $e164;
            $cdr->callerid = env('TWILIO_FROM');
            $cdr->message = $say;


            /*
             * Check that the dialed number is 10 digits
             */
            if(strlen($number) != 10)
            {

                $cdr->successful = false;
                $cdr->failurereason = "The Dailed Number " . $number . " is not 10 digits in length";
                $cdr->save();

                Throw new TwilioException("The Dailed Number " . $number . " is not 10 digits in length");

            }

            /*
             * Check the call type
             */
            if(strtolower($type) == 'voice')
            {

                $callType = 'call';
                $say = 'http://twimlets.com/message?Message%5B0%5D=' . urlencode($say);
                $cdr->calltype = 'Phone Call';


            } elseif(strtolower($type) == 'text') {

                $callType = 'message';
                $cdr->calltype = 'Text Message';

            } else {

                $cdr->successful = false;
                $cdr->failurereason = "The call type " . $type . " is invalid";
                $cdr->save();

                Throw new TwilioException("The call type " . $type . " is invalid");

            }

            /*
             * Attempt the phone call
             */
            try {

                $this->twilio->$callType($e164, $say);

            } catch(Exception $e) {

                $cdr->successful = false;
                $cdr->failurereason = $e->getMessage();
                $cdr->save();

                Throw new TwilioException($e->getMessage());

            }

            $cdr->successful = true;
            $cdr->save();

            sleep(2);

        }
    }
}
