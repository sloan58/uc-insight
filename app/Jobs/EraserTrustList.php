<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class EraserTrustList extends Job implements SelfHandling
{

    private $eraserList;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $eraserList)
    {
        $this->eraserList = $eraserList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
