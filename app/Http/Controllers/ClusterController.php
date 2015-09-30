<?php

namespace App\Http\Controllers;

use App\Cluster;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

class ClusterController extends Controller
{

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
        $clusters = Cluster::all();

        return view('cluster.index', compact('clusters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('cluster.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'ip' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $cluster = new Cluster();
        $cluster->name = $request->name;
        $cluster->ip = $request->ip;
        $cluster->username = $request->username;
        $cluster->password = $request->password;

        $cluster->save();

        Flash::success('Cluster added!');

        return redirect()->action('ClusterController@index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $cluster
     * @return Response
     */
    public function edit($cluster)
    {
        return view('cluster.edit', compact('cluster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     * @internal param int $id
     */
    public function update(Request $request, Cluster $cluster)
    {
        $cluster->name = $request->name;
        $cluster->ip = $request->ip;
        $cluster->username = $request->username;
        $cluster->password = checkPassword($cluster->password,$request->password);
        $cluster->save();

        Flash::success('Cluster info updated!');

        return redirect()->action('ClusterController@index');

    }

}
