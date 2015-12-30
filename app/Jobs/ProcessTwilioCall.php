<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class PlaceTwilioCall
 * @package App\Jobs
 */
class ProcessTwilioCall extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, DispatchesJobs, SerializesModels;

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
        $this->callList = $callList;
    }

    /**
     * Execute the job.
     * Handle the ProcessTwilioCall Request
     */
    public function handle()
    {
        foreach($this->callList as $call)
        {
            $this->dispatch(new PlaceTwilioCall($call));
        }
    }
}
