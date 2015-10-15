<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Services\AxlSoap;
use App\Services\SqlSelect;
use App\Sql;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class SqlController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $clusters = Cluster::lists('name','id');
        return view('sql.index', compact('clusters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $sql = $request->input('sqlStatement');

        $cluster = Cluster::where('active', true)->first();

        $axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            'https://' . $cluster->ip . ':8443/axl/',
            $cluster->username,
            $cluster->password
        );

        $data = executeQuery($axl,$sql);

        if(isset($data->faultstring))
        {
            Flash::error($data->faultstring);
            return view('sql.index', compact('sql'));

        } else {

            $format = getHeaders($data);

            Sql::firstOrCreate([
                'sql' => $sql
            ]);

        }

        return view('sql.index',compact('data','format','sql'));
    }

    public function show(Sql $sql)
    {

        $sql = $sql->sql;

        $cluster = Cluster::where('active', true)->first();

        $axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            'https://' . $cluster->ip . ':8443/axl/',
            $cluster->username,
            $cluster->password
        );

        $data = executeQuery($axl,$sql);

        if(isset($data->faultstring))
        {
            Flash::error($data->faultstring);
            return view('sql.index', compact('sql'));

        } else {

            $format = getHeaders($data);

        }

        return view('sql.index',compact('data','format','sql'));
    }

    public function history()
    {
        $sqls = Sql::all();

        return view('sql.history', compact('sqls'));

    }
}
