<?php

namespace App\Http\Controllers;

use App\Services\AxlSoap;
use App\Services\RisSoap;
use Goutte\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GetFirmwareController extends Controller
{

    public function index() {

    	$result = executeQuery('SELECT pkid, name FROM devicepool');
    	foreach($result as $devicePool)
    	{
			$devicePoolList[$devicePool->pkid] = $devicePool->name;
    	}
    	return view('firmware.index', compact('devicePoolList'));
    }

    public function store(Request $request) {
    	
    	$devicePool = ($request->input('devicepool'));
		$result = executeQuery("SELECT name FROM device WHERE fkdevicepool = '$devicePool' AND tkclass = '1' AND tkdeviceprofile = 0");
		
		foreach($result as $phone)
		{
			$devices[] = $phone->name;
		}

        $risArray = createRisPhoneArray($devices);
        $sxml = new RisSoap();
        $risResults = $sxml->getDeviceIP($risArray);
        $finalReport = processRisResults($risResults,$risArray);

            foreach ($finalReport as $key => $value)
            {
                if (!filter_var($value['IpAddress'], FILTER_VALIDATE_IP)) {
                    $finalReport[$key]['Firmware'] = 'Unavailable';
                    continue;
                }

                $client = new Client();
//                $crawler = $client->request('GET', 'http://' . $value['IpAddress']);
                $crawler = $client->request('GET', 'http://' . '10.132.215.48');

                if ($crawler->filter('DIV TABLE TR')->eq(5)->filter('td')->eq(2)->count()) {
                    $finalReport[$key]['Firmware'] = $crawler->filter('DIV TABLE TR')->eq(5)->filter('td')->eq(2)->text();
                }
            }

        return view('firmware.show', compact('finalReport'));
    }
}
