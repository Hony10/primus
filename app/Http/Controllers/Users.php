<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Users extends Controller
{
    /**
     * List all users
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            [
                'users' => User::all(),
                'total' => User::all()->count(),
                'totalAgents' => User::where('roles', 'LIKE', '%Agent%')->count(),
            ]
        );
    }

    /**
     * Display a specific user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('username', $id)->first();
        if (!$user) {
            abort(404, 'User not found');
        }
        return response()->json($user);
    }

    /**
     * Update a specific user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'password' => 'min:6'
            ]
        );

        $user = User::where('username', $id)->first();
        if (!$user) {
            abort(404, 'User not found');
        }

        $user->first_name = $request->input('firstName', $user->first_name);
        $user->last_name = $request->input('lastName', $user->last_name);
        $user->username = $request->input('username', $user->username);
        $user->enabled = $request->input('enabled', $user->enabled);
        $user->roles = $request->input('roles', $user->roles);
        if ($password = $request->input('password', false)) {
            $user->setPassword($password);
        }

        $user->save();

        return response()->json($user);
    }

}
