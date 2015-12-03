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

    public function index()
    {
        return view('autodialer.index');
    }


    public function store(Request $request)
    {
        $number = substr($request->number, -10);
        $say = $request->say;

        $this->dispatch(new PlaceTwilioCall([[$number,$say]]));

        Flash::success('Phone Call Submitted!  Check the call logs for status.');

        return redirect()->action('AutoDialerController@index');

    }

    public function bulkIndex()
    {
        return view('autodialer.bulk.index');
    }


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
