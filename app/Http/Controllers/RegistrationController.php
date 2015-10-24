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
        set_time_limit(0);

        $sxml = new RisSoap();
        $data = executeQuery('SELECT name FROM device');

        $deviceList = [];

        foreach($data as $i)
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
