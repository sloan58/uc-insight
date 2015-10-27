<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\ControlCenterSoap;
use App\Services\RisSoap;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display Lavachart graph for CUCM devices
     *
     * @return \Illuminate\View\View
     */
    public function deviceCounts()
    {
        $data = executeQuery('SELECT t.name, count(*) AS num FROM device d INNER JOIN typemodel t ON d.tkmodel = t.enum GROUP BY t.name ORDER BY num DESC');

        $phoneCounts = \Lava::DataTable();

        $phoneCounts->addStringColumn('Phone Model')
            ->addNumberColumn('Count');

        foreach ($data as $key => $val)
        {
            $phoneCounts->addRow([$val->name,(int)$val->num]);
        }

        $piechart = \Lava::DonutChart('Cisco IP Phones')
            ->setOptions([
                'datatable' => $phoneCounts,
                'title' => 'Cluster A IP Phones Stats',
                'height' => 1000,
                'width' => 2000
            ]);

        return view('lavachart/index', compact('piechart'));
    }

    /**
     * Get all devices from CUCM device
     * table and return registration status.
     *
     * @return \Illuminate\View\View
     * @throws \App\Exceptions\SoapException
     */
    public function deviceRegistration()
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

    /**
     * Return the service status for
     * all CUCM cluster nodes and services
     *
     * @return \Illuminate\View\View
     */
    public function systemServices()
    {
        $data = executeQuery('SELECT name FROM processnode WHERE tkprocessnoderole = 1 AND name != "EnterpriseWideData"');

        $clusterStatus = [];
        foreach($data as $node)
        {
            $ris = new ControlCenterSoap();
            $clusterStatus[$node->name] = $ris->getServiceStatus();
        }

        return view('service.show', compact('clusterStatus'));
    }
}
