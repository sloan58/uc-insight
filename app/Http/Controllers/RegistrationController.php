<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Services\AxlSoap;
use App\Services\PreparePhoneList;
use App\Services\RisSoap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    public function index()
    {
        $clusters = Cluster::lists('name','id');

        return view('registration.index', compact('clusters'));
    }

    public function store(Request $request)
    {
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

        $res = $axl->executeSQLQuery('SELECT name FROM device');

        $deviceList = [];

        foreach($res->return->row as $i)
        {
            $deviceList[] = $i->name;
        }

        $deviceList = createRisPhoneArray($deviceList);
        $finalReport = [];

        foreach(array_chunk($deviceList, 1000, true) as $chunk)
        {
            $SelectCmDeviceResult = $sxml->getDeviceIp($chunk);
            $res = processRisResults($SelectCmDeviceResult,$chunk);

            foreach($res as $i)
            {
                $finalReport[] = $i;
            }
        }

        return view('registration.show', compact('finalReport'));
    }
}
