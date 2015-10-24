<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Http\Requests;
use App\Jobs\ServiceStatusJob;
use App\Services\ControlCenterSoap;

/**
 * Class ServiceStatusController
 * @package App\Http\Controllers
 */
class ServiceStatusController extends Controller
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

        return view('service.index', compact('clusters'));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     * @internal param Request $request
     */
    public function store()
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
