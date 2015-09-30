<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $cluster
     * @return Response
     */
    public function edit($user)
    {
        $roles = Role::lists('name','name');

        return view('user.edit', compact('user','roles'));
    }
}
