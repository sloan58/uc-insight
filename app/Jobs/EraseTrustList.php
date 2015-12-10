<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\AxlSoap;
use App\Services\PhoneDialer;
use Illuminate\Contracts\Bus\SelfHandling;

class EraseTrustList extends Job implements SelfHandling
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
        $macList = array_column($this->eraserList, 'mac');
        $formattedEraserList = generateEraserList($macList);

        foreach($this->eraserList as $row)
        {
            $key = array_search($row['mac'], array_column($formattedEraserList, 'DeviceName'));
            $formattedEraserList[$key]['type'] = $row['type'];
        }

        foreach($formattedEraserList as $device)
        {
            if($device['IpAddress'] == "Unregistered/Unknown")
            {
                continue;
            }

            $keys = setKeys($device['Model'],$device['type']);

            $dialer = new PhoneDialer($device['IpAddress']);
            // $status = $dialer->dial($tleObj,$keys);
            $status = $dialer->dial($keys);

            dd($status);
        }
    }
}
