<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Jobs\ServiceStatusJob;
use App\Services\AxlSoap;
use App\Services\ControlCenterSoap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class ServiceStatusController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $clusters = Cluster::lists('name','id');

        return view('service.index', compact('clusters'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $cluster = Cluster::where('active',true)->first();
        $axl = new AxlSoap($cluster);

        $data = executeQuery($axl,'SELECT name FROM processnode WHERE tkprocessnoderole = 1 AND name != "EnterpriseWideData"');

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

            $clusterStatus = [];
            foreach($data as $node)
            {
                $ris = new ControlCenterSoap($cluster);
                $clusterStatus[$node->name] = $ris->getServiceStatus();
            }

            return view('service.show', compact('clusterStatus'));
        }
    }
}
