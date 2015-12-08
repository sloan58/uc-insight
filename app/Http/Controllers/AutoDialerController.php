<?php

namespace App\Http\Controllers;

use App\Jobs\PlaceTwilioCall;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Keboola\Csv\CsvFile;
use Laracasts\Flash\Flash;

class AutoDialerController extends Controller
{

    /**
     * Create a new controller instance.
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
        return view('autodialer.index');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeCall(Request $request)
    {
        $number = substr($request->number, -10);
        $say = $request->say;
        $type = $request->type;

//        $this->dispatch(new PlaceTwilioCall([[$number,$say,$type]]));

        $call = (new PlaceTwilioCall([[$number,$say,$type]]))->delay(60);

        $this->dispatch($call);
        
        Flash::success('Phone Call Submitted!  Check the call logs for status.');

        return redirect()->action('AutoDialerController@index');

    }

    /**
     * @return \Illuminate\View\View
     */
    public function bulkIndex()
    {
        return view('autodialer.bulk.index');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkStore(Request $request)
    {
        $file = $request->file('file');

        if ($file->getClientMimeType() != "text/csv" && $file->getClientOriginalExtension() != "csv")
        {
            Flash::error('File type invalid.  Please use a CSV file format.');
            return redirect()->back();
        }

        $csvFile = new CsvFile($file);

        $csv = '';

        foreach($csvFile as $row)
        {
            $csv[] = $row;
        }
        $this->dispatch(new PlaceTwilioCall($csv));

        Flash::success('Phone Call Submitted!  Check the call logs for status.');

        return redirect()->action('AutoDialerController@index');
    }

}
