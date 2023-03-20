<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreateUser extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input data
        $request->validate(
            [
                'username' => 'required|email',
                'firstName' => 'required',
                'lastName' => 'required',
                'password' => 'required|min:6',
            ]
        );

        // Check if there is a user with the same username first
        $user = User::where('username', $request->input('username'))->first();
        if ($user) {
            abort(400, 'A user with the same name already exists');
        }

        // Create the user account
        $user = User::create(
            [
                'username' => $request->input('username'),
                'first_name' => $request->input('firstName'),
                'last_name' => $request->input('lastName'),
                'roles' => $request->input('roles'),
                'enabled' => 1,
            ]
        );

        if (!$user) {
            abort(400, 'Failed to create user account, a server error occurred');
        }

        $user->setPassword($request->input('password'));
        $user->save();

        return response()->json($user);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
