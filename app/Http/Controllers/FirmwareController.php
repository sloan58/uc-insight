<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Services\AxlSoap;
use App\Services\RisSoap;
use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Log;

class FirmwareController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $clusters = Cluster::lists('name','id');

        return view('firmware.index', compact('clusters'));
    }

    public function store(Request $request)
    {
        set_time_limit(0);

        //TODO: Fix timeout issue with Cluster A

        $cluster = Cluster::where('id',$request->cluster)->first();

        $axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            'https://' . $cluster->ip . ':8443/axl/',
            $cluster->username,
            $cluster->password
        );

        $sxml = new RisSoap(
            app_path() . '/CiscoAPI/sxml/schema/RISAPI.wsdl',
            'https://' . $cluster->ip . ':8443/realtimeservice/services/RisPort',
            $cluster->username,
            $cluster->password
        );

        $res = $axl->executeSQLQuery('SELECT name FROM device WHERE tkclass = 1 AND tkdeviceprofile = 0 AND tkmodel IN ("435","436","437")');

        $deviceList = [];

        foreach($res->return->row as $i)
        {
            $deviceList[] = $i->name;
        }

        $deviceList = createRisPhoneArray($deviceList);
        $deviceDetails = [];

        foreach(array_chunk($deviceList, 1000, true) as $chunk)
        {
            $SelectCmDeviceResult = $sxml->getDeviceIp($chunk);
            $res = processRisResults($SelectCmDeviceResult,$chunk);

            foreach($res as $i)
            {
                $deviceDetails[] = $i;
            }
        }

        $finalReport = [];

        foreach($deviceDetails as $device)
        {
            if (!filter_var($device['IpAddress'], FILTER_VALIDATE_IP)) {

                $device['Firmware'] = 'Unavailable';

            } else {

                    $guzzle = new Client([
                        'base_url' => 'http://' . $device['IpAddress'],
                        'defaults' => [
                            'headers' => [
                                'Accept' => 'application/xml',
                                'Content-Type' => 'application/xml'
                            ],
                            'verify' => false,
                        ]
                    ]);

                    $device = messagePhone($guzzle, $device, 0);
                }

            Log::info('Updated $finalReport array', [$device]);
            $finalReport[] = $device;
        }

        return view('firmware.show', compact('finalReport'));

    }
}
