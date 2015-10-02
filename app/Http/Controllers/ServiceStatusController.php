<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Jobs\ServiceStatusJob;
use App\Services\AxlSoap;
use App\Services\ControlCenterSoap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ServiceStatusController extends Controller
{

    public function index()
    {
        $clusters = Cluster::lists('name','id');

        return view('service.index', compact('clusters'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $cluster = Cluster::where('id',$request->cluster)->first();

        $axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            'https://' . $cluster->ip . ':8443/axl/',
            $cluster->username,
            $cluster->password
        );

        $res = $axl->executeSQLQuery('SELECT name FROM processnode WHERE tkprocessnoderole = 1 AND name != "EnterpriseWideData"');

        $clusterNodes = $res->return->row;

        $clusterStatus = [];

        foreach($clusterNodes as $node)
        {
            $ris = new ControlCenterSoap($node->name, $cluster->username, $cluster->password);
            $res = $ris->getServiceStatus();
            $clusterStatus[$node->name] = $res;
        }

        return view('service.show', compact('clusterStatus'));
    }

}
