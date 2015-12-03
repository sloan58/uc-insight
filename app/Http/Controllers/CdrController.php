<?php

namespace App\Http\Controllers;

use App\Cdr;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CdrController extends Controller
{
    public function index()
    {
        $cdrs = Cdr::all();

        return view('cdr.index', compact('cdrs'));
    }
}
