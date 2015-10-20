<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));

    }

    public function create()
    {
        $roles = Role::lists('name','id');
        return view('user.create', compact('roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $cluster
     * @return Response
     */
    public function edit($user)
    {
        $roles = Role::lists('name','id');
        $user_roles = $user->roles->lists('id');

        return view('user.edit', compact('user','roles','user_roles'));
    }

    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt(checkPassword($user->password,$request->password));
        $user->save();

        if($request->input('role_list'))
        {
            $user->roles()->sync($request->input('role_list'));
        }

        Flash::success('User Updated!');

        return redirect()->action('UserController@index');

    }

    public function store(Request $request)
    {

        $user = User::firstOrCreate([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        if($request->input('role_list'))
        {
            $user->roles()->sync($request->input('role_list'));
        }

        Flash::success('User Created!');

        return redirect()->action('UserController@index');

    }

    public function destroy(Request $request, User $user)
    {
        User::destroy($user->id);

        Flash::success('User Deleted!');

        return redirect()->action('UserController@index');
    }
}
