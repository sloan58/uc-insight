<?php

namespace App\Http\Controllers;

use App\Phone;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhoneController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Phone $phone)
    {
        return view('phone.show', compact('phone'));
    }
}
