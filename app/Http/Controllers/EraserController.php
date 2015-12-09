<?php

namespace App\Http\Controllers;

use App\Eraser;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use App\Jobs\EraseTrustList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class EraserController extends Controller
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
    public function itlIndex()
    {
        $itls = Eraser::where('eraser_type','itl')->get();

        return view('itl.index', compact('itls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function itlStore(Request $request)
    {
        Log::info('Received ITL Erase request for: '.$request->input('macAddress'));

        $this->dispatch(
            new EraseTrustList([
                ['MAC' => $request->input('macAddress'), 'TLE' => 'itl']
            ])
        );

        Flash::success('Processed Request.  Check table below for status.');
        return redirect('itl');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function ctlIndex()
    {
        $ctls = Eraser::where('eraser_type','ctl')->get();

        return view('ctl.index', compact('ctls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function ctlStore(Request $request)
    {
        Log::info('Received CTL Erase request for: '.$request->input('macAddress'));

        $this->dispatch(
            new EraseTrustList($request->input('macAddress'),'ctl')
        );

        Flash::success('Processed Request.  Check table below for status.');
        return redirect('ctl');
    }
}
