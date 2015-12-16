<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\AxlSoap;
use App\Services\RisSoap;
use Goutte\Client;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class GetPhoneFirmware
 * @package App\Jobs
 */
class GetPhoneFirmware extends Job implements SelfHandling
{
    protected $devices;

    /**
     * Create a new job instance.
     *
     * @param $devices
     * @return \App\Jobs\GetPhoneFirmware
     */
    public function __construct($devices)
    {
        $this->devices = $devices;
    }

    /**
     * Execute the job.
     *
     * @internal param $devices
     * @return void
     */
    public function handle()
    {
        $risArray = createRisPhoneArray($this->devices);
        $sxml = new RisSoap();
        $risResults = $sxml->getDeviceIP($risArray);
        $finalReport = processRisResults($risResults,$risArray);

        foreach ($finalReport as $key => $value)
        {
            if (!filter_var($value['IpAddress'], FILTER_VALIDATE_IP)) {
                $finalReport[$key]['Firmware'] = 'Unavailable';
                $finalReport[$key]['Model'] = 'Unavailable';
                continue;
            }

            $res = executeQuery('SELECT name FROM typeproduct WHERE enum = "' . $value['Product'] . '"');
//            $finalReport[$key]['Model'] = $res[0]->name;
            $finalReport[$key]['Model'] = 'Cisco 9971';

            $client = new Client();
//                $crawler = $client->request('GET', 'http://' . $value['IpAddress']);
            $crawler = $client->request('GET', 'http://' . '10.188.52.25');

            switch($finalReport[$key]['Model']) {
                case 'Cisco 7945':
                case 'Cisco 7965':
                case 'Cisco 7975':
                    $finalReport[$key]['Firmware'] = $crawler->filter('DIV TABLE TR')->eq(5)->filter('td')->eq(2)->text();
                    break;
                case 'Cisco 9951':
                    $finalReport[$key]['Firmware'] = $crawler->filter('DIV TABLE TR')->eq(3)->filter('td')->eq(2)->text();
                    break;
                case 'Cisco 9971':
                    $finalReport[$key]['Firmware'] = $crawler->filter('DIV TABLE TR')->eq(5)->filter('td')->eq(2)->text();
                    break;
                default:
                    $finalReport[$key]['Firmware'] = 'Unavailable';
            }
        }
        return $finalReport;
    }
}
