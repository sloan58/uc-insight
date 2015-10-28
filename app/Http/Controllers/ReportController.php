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

        return view('reports.device.counts', compact('piechart'));
    }
}
