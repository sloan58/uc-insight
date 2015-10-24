<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Services\AxlSoap;
use App\Services\PreparePhoneList;
use App\Services\RisSoap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

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
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        set_time_limit(0);

        $cluster = Cluster::where('active',true)->first();
        $axl = new AxlSoap($cluster);
        $sxml = new RisSoap($cluster);

        $data = executeQuery($axl,'SELECT name FROM device');

        if(isset($data->faultstring))
        {
            if($data->faultstring == '')
            {
                Flash::error('Server Error.  Check your WSDL Version....');
            } else {
                Flash::error($data->faultstring);
            }

            return view('registration.index');

        } else {

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
}
