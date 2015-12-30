<?php

namespace App\Http\Controllers;

use App\Sql;
use App\Cluster;
use App\Http\Requests;
use App\Services\SqlSelect;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $sql = $request->input('sqlStatement');

        $data = executeQuery($sql);
        $format = getHeaders($data);

        Sql::firstOrCreate([
            'sqlhash' => md5($sql),
            'sql' => $sql
        ]);

        return view('sql.index',compact('data','format','sql'));
    }

    public function show(Sql $sql)
    {

        $sql = $sql->sql;

        $data = executeQuery($sql);
        $format = getHeaders($data);

        return view('sql.index',compact('data','format','sql'));
    }

    public function history()
    {
        $sql = Sql::all();

        return view('sql.history', compact('sql'));

    }

    public function favorites()
    {
        $favorites = \Auth::user()->sqls()->get();

        return view('sql.favorites', compact('favorites'));
    }
}
