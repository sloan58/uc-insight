<?php

namespace App\Jobs;

use App\Phone;
use App\Eraser;
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
        $formattedEraserList = generateEraserList($this->eraserList);

        foreach($formattedEraserList as $device)
        {
            //Create the Phone model
            $phone = Phone::firstOrCreate([
                'mac' => $device['DeviceName'],
                'description' => $device['Description']
            ]);

            //Start creating Eraser
            $tleObj = Eraser::create([
                'phone_id' => $phone->id,
                'ip_address' => $device['IpAddress'],
                'eraser_type' => $device['type'],
            ]);

            if(isset($device['bulk_id']))
            {
                $tleObj->bulks()->attach($device['bulk_id']);
            }

            if($device['IpAddress'] == "Unregistered/Unknown")
            {
                $tleObj->result = 'Fail';
                $tleObj->failure_reason = 'Unregistered/Unknown';
                $tleObj->save();
                continue;
            }

            $keys = setKeys($device['Model'],$device['type']);

            if(!$keys)
            {
                $tleObj->result = 'Fail';
                $tleObj->failure_reason = 'Unsupported Model';
                $tleObj->save();
                return;
            }
            $dialer = new PhoneDialer($device['IpAddress']);
            $status = $dialer->dial($tleObj,$keys);

            //Successful if returned true
            $passFail = $status ? 'Success' : 'Fail';
            $tleObj->result = $passFail;
            $tleObj->save();
        }
    }
}
