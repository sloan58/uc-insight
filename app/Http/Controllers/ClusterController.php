<?php

namespace App\Http\Controllers;

use DB;
use App\Cluster;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $directories = Storage::directories('axl/');
        $versions= [];
        foreach($directories as $directory)
        {
            preg_match('/\/(.*)/',$directory,$matches);
            $versions[$matches[1]] = $matches[1];
        }

        return view('cluster.create', compact('versions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
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

        if($request->active)
        {
            DB::table('clusters')->update(['active' => false]);
            $cluster->active = true;

        }

        $cluster->name = $request->name;
        $cluster->ip = $request->ip;
        $cluster->version = $request->version;
        $cluster->username = $request->username;
        $cluster->password = $request->password;
        $cluster->user_type = $request->user_type;


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
    public function edit(Cluster $cluster)
    {
        $directories = Storage::directories('axl/');
        $versions= [];
        foreach($directories as $directory)
        {
            preg_match('/\/(.*)/',$directory,$matches);
            $versions[$matches[1]] = $matches[1];
        }
        return view('cluster.edit', compact('cluster','versions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Cluster $cluster
     * @return Response
     * @internal param int $id
     */
    public function update(Request $request, Cluster $cluster)
    {
        if($request->active)
        {
            DB::table('clusters')->update(['active' => false]);
            $cluster->active = true;

        }

        $cluster->name = $request->name;
        $cluster->ip = $request->ip;
        $cluster->username = $request->username;
        $cluster->user_type = $request->user_type;
        $cluster->version = $request->version;
        $cluster->verify_peer = $request->verify_peer ? true : false;
        $cluster->password = checkPassword($cluster->password,$request->password);
        $cluster->save();

        Flash::success('Cluster info updated!');

        return redirect()->action('ClusterController@index');

    }

    public function destroy(Cluster $cluster)
    {
        Cluster::destroy($cluster->id);

        Flash::success('Cluster Deleted!');

        return redirect()->action('ClusterController@index');
    }
}
