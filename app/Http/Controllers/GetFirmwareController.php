<?php

namespace App\Http\Controllers;

use App\Jobs\GetPhoneFirmware;
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

        $finalReport = $this->dispatch(
            new GetPhoneFirmware($devices)
        );

        return view('firmware.show', compact('finalReport'));
    }
}
