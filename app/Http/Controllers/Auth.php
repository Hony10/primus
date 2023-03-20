<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Auth extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Attempt to authenticate now
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Are we already logged in?
        if ($request->session()->get('user')) {
            abort(400, 'Already logged in');
        }

        // Validate data
        $request->validate(
            [
                'username' => 'required|email',
                'password' => 'required',
            ]
        );

        // Validate username and password
        $user = User::where('username', $request->input('username', ''))->first();
        if (!$user) {
            abort(400, 'Invalid credentials');
        }

        if (!$user->verifyPassword($request->input('password', ''))) {
            abort(400, 'Invalid credentials');
        }

        // Successfully authenticated, set appropriate session variables
        $request->session()->put('user', $user->id);
        $request->session()->put('username', $user->username);
        $request->session()->put('first_name', $user->first_name);
        $request->session()->put('last_name', $user->last_name);
        $request->session()->put('roles', $user->roles);
        $request->session()->put('expires', Carbon::now()->addHours(24)->unix());

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Logout and destroy the session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->session()->invalidate();
    }
}
