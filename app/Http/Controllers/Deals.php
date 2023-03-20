<?php

/**
 * Deals Model File Doc Comment
 * 
 * PHP Version 8.0.2
 * 
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;

/**
 * Deals Model
 *
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class Deals extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deals = Deal::all();
        $result = [];
        foreach ($deals as $deal) {
            $result[] = [
                'id' => $deal->id,
                'name' => $deal->name,
                'slug' => $deal->slug,
                'enabled' => $deal->enabled,
                'colour' => $deal->colour,
                'deduction' => $deal->deduction,
                'deduction_ivan' => $deal->deduction_ivan,
                'created_at' => $deal->created_at,
                'updated_at' => $deal->updated_at,
                'assignments' => json_decode($deal->assignments, false),
            ];
        };
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request The request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:6',
                'slug' => 'required|regex:/^[a-zA-Z0-9-]+$/',
                'colour' => 'required',
                'deduction' => 'required',
                'deduction_ivan' => 'required',
                'enabled' => 'required|integer',
                'assignments' => 'required',
            ]
        );

        // Check if there is a deal with the same slug
        $deal = Deal::where('slug', '=', $request->input('slug'))->first();
        if ($deal) {
            abort(
                400,
                'A deal with a similar name already exists, please choose ' .
                    'a different name'
            );
        }

        $deal = Deal::create(
            [
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'colour' => $request->input('colour'),
                'deduction' => $request->input('deduction'),
                'deduction_ivan' => $request->input('deduction_ivan'),
                'enabled' => $request->input('enabled'),
                'assignments' => json_encode($request->input('assignments')),
            ]
        );

        return response()->json($deal);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id The deal id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = Deal::where('slug', $id)->first();
        if (!$deal) {
            // Attempt to get deal by id instead
            $deal = Deal::where('id', $id)->first();
            if (!$deal) {
                abort(404, 'Deal not found');
            }
        }

        return response()->json(
            [
                'id' => $deal->id,
                'name' => $deal->name,
                'slug' => $deal->slug,
                'enabled' => $deal->enabled,
                'colour' => $deal->colour,
                'deduction' => $deal->deduction,
                'deduction_ivan' => $deal->deduction_ivan,
                'created_at' => $deal->created_at,
                'updated_at' => $deal->updated_at,
                'assignments' => json_decode($deal->assignments, false),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param int                      $id      The deal id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deal = Deal::where('slug', $id)->first();
        if (!$deal) {
            // Attempt to get deal by id instead
            $deal = Deal::where('id', $id)->first();
            if (!$deal) {
                abort(404, 'Deal not found');
            }
        }

        $assignments = $request->input('assignments', '');
        if ($assignments !== '') {
            $deal->assignments = json_encode($assignments);
        }

        $deal->fill(
            [
                'name' => $request->input('name', $deal->name),
                'slug' => $request->input('slug', $deal->slug),
                'colour' => $request->input('colour', $deal->colour),
                'deduction' => $request->input('deduction', $deal->deduction),
                'deduction_ivan' => $request->input(
                    'deduction_ivan', $deal->deduction_ivan
                ),
                'enabled' => $request->input('enabled', $deal->enabled),
            ]
        );

        $deal->save();

        return response()->json($deal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id The deal ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = Deal::where('slug', $id)->first();
        if (!$deal) {
            // Attempt to get deal by id instead
            $deal = Deal::where('id', $id)->first();
            if (!$deal) {
                abort(404, 'Deal not found');
            }
        }
        $deal->delete();
        return response()->json(['status' => 'success']);
    }
}
