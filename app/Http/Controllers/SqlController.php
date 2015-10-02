<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Services\AxlSoap;
use App\Services\SqlSelect;
use App\Sql;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SqlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
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

        Sql::firstOrCreate([
            'sql' => $sql
        ]);

//        $cluster = Cluster::where('id',$request->cluster)->first();
        $cluster = Cluster::where('id','12')->first();

        $axl = new AxlSoap(
            app_path() . '/CiscoAPI/axl/schema/8.5/AXLAPI.wsdl',
            'https://' . $cluster->ip . ':8443/axl/',
            $cluster->username,
            $cluster->password
        );

        $data = executeQuery($axl,$sql);

        if(!isset($data->faultstring))
        {
            $format = getHeaders($data);
        }

        return view('sql.index',compact('data','format','sql'));
    }

    public function show($sql)
    {
//        TODO: Fix this whole thing....

        $sqlSelect = new SqlSelect();
        $data = $sqlSelect->executeQuery($sql);
        if(!isset($data->faultstring))
        {
            $format = $sqlSelect->getHeaders($data);
        }
        return view('sql.index',compact('data','format','sql'));
    }

    public function history()
    {
        $sqls = Sql::all();

        return view('sql.history', compact('sqls'));

    }
}
