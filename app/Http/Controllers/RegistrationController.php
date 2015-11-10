<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Http\Requests;
use App\Services\RisSoap;
use App\Services\PreparePhoneList;

/**
 * Class RegistrationController
 * @package App\Http\Controllers
 */
class RegistrationController extends Controller
{

    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clusters = Cluster::lists('name','id');

        return view('registration.index', compact('clusters'));
    }

    /**
     * @return \Illuminate\View\View
     * @internal param Request $request
     */
    public function store()
    {
        // Avoid PHP timeouts when querying large clusters
        set_time_limit(0);

        // Create the RisPort Soap Client
        $sxml = new RisSoap();

        // Query CUCM for device name and model
        $data = executeQuery('SELECT d.name devicename, t.name model FROM device d INNER JOIN typemodel t ON d.tkmodel = t.enum');

        // $deviceList will hold our array for RisPort
        $deviceList = [];

        // Loop SQL data and assign devicename to $deviceList
        foreach($data as $i)
        {
            $deviceList[] = $i->devicename;
        }

        // Generate our ['Item' => deviceName] array for RisPort
        $deviceList = createRisPhoneArray($deviceList);

        // $finalReport is the array we'll send to the view
        $finalReport = [];

        // RisPort has a 1000 device query limit.  Use chunk to divide it up
        foreach(array_chunk($deviceList, 1000, true) as $chunk)
        {
            // Query RisPort for device registration info
            $SelectCmDeviceResult = $sxml->getDeviceIp($chunk);

            // Call a function to process the RisPort results and get IP's
            $res = processRisResults($SelectCmDeviceResult,$chunk);

            // Loop each RisPort result and obtain the model
            foreach($res as $key => $value)
            {

                // Look into the SQL data to find the model information
                foreach($data as $device)
                {
                    if($value['DeviceName'] == $device->devicename)
                    {
                        $res[$key]['Model'] = $device->model;
                    }
                }
            }

            // Combine 'chunks' of the RisPort data
            $finalReport = array_merge($finalReport,$res);

        }

        return view('registration.show', compact('finalReport'));
    }
}
