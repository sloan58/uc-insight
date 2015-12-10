<?php

namespace App\Http\Controllers;

use App\Bulk;
use App\Eraser;
use Carbon\Carbon;
use App\Http\Requests;
use Keboola\Csv\CsvFile;
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

        return view('eraser.itl.index', compact('itls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function itlStore(Request $request)
    {
        
        $this->dispatch(
            new EraseTrustList([
                ['mac' => $request->input('macAddress'), 'type' => 'itl']
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

        return view('eraser.ctl.index', compact('ctls'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function ctlStore(Request $request)
    {
       $this->dispatch(
            new EraseTrustList([
                ['mac' => $request->input('macAddress'), 'type' => 'ctl']
            ])
        );

        Flash::success('Processed Request.  Check table below for status.');
        return redirect('ctl');
    }

     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function bulkIndex()
    {
        $bulks = Bulk::all();

        return view('eraser.bulk.index', compact('bulks'));
    }

    /**
     * @param  Bulk
     * @return Response
     */
    public function bulkShow(Bulk $bulk)
    {
        return view('eraser.bulk.show', compact('bulk'));
    }

    /**
     * @return Response
     */
    public function bulkCreate()
    {
        return view('eraser.bulk.create');
    }

    /**
     * @param  Request
     * @return Response
     */
    public function bulkStore(Request $request)
    {

        $file = $request->file('file');
        $fileName = $request->input('file_name');
        $fileName = $fileName ?: $file->getClientOriginalName();

        $bulk = Bulk::create([
            'file_name' => $fileName
        ]);

        if ($file->getClientMimeType() != "text/csv" && $file->getClientOriginalExtension() != "csv")
        {
            $bulk->result = "Invalid File Type";
            $bulk->mime_type = $file->getClientMimeType();
            $bulk->file_extension = $file->getClientOriginalExtension();
            $bulk->save();

            Flash::error('File type invalid.  Please use a CSV file format.');
            return redirect()->back();
        }

        $csvFile = new CsvFile($file);

        foreach($csvFile as $row)
        {
            $indexArray[] = $row;
        }

        for($i=0;$i<count($indexArray);$i++)
        {
            $eraserArray[$i]['mac'] = $indexArray[$i][0];
            $eraserArray[$i]['type'] = $indexArray[$i][1];
            $eraserArray[$i]['bulk_id'] = $bulk->id;
        }

        $this->dispatch(
            new EraseTrustList($eraserArray)
        );

        $bulk->result = "Processed";
        $bulk->mime_type = $file->getClientMimeType();
        $bulk->file_extension = $file->getClientOriginalExtension();
        $bulk->process_id = $fileName . '-' . Carbon::now()->timestamp;
        $bulk->save();

        $file->move(storage_path() . '/uploaded_files/',$fileName);

        Flash::success("File loaded successfully!  Check the Bulk Process table for progress on $bulk->process_id.");

        $bulks = Bulk::all();
        return view('eraser.bulk.index', compact('bulks'));

    }
}
