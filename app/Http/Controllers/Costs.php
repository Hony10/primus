<?php

/**
 * Costs Controller File Doc Comment
 * 
 * PHP Version 8.0.3
 * 
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */


namespace App\Http\Controllers;

use App\Models\Commissions;
use Illuminate\Http\Request;
use App\Models\Costs as Cost;
use Carbon\Carbon;

/**
 * Costs Controller File Doc Comment
 *
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class Costs extends Controller
{
    /**
     * List all costs that match the specified criteria
     *
     * @param \Illuminate\Http\Request $request The request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all costs for this agent
        $agent = $request->input('agent', false);
        $dateFrom = $request->input('dateFrom', false);
        $dateTo = $request->input('dateTo', false);

        if (!$agent || !$dateFrom || !$dateTo) {
            abort(400, 'An agent is required');
        }

        $costsQuery = Cost::where('agent', $agent)
            ->where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo);
        
        $costsCount = $costsQuery->count();
        $costs = $costsQuery->get();

        // Get all commissions for this agent
        $commissions = Commissions::getCommissions($agent, $dateFrom, $dateTo);
        $totalCommissions = 0.00;
        foreach ($commissions as $commission) {
            $totalCommissions += $commission->commission_value;
        }
        $totalCommissions = round($totalCommissions, 2);
        $totalCosts = 0.00;
        foreach ($costs as $cost) {
            $totalCosts += $cost->value;
        }

        // Also get a total summary for this agent, we need total commissions,
        // total costs and total commission payments
        // $totalCommissions = Commissions::getCommissionTotal($agent);
        // $totalCosts = Cost::where('agent', $agent)
        //     ->sum('value');
        

        // Calculate the opening balance
        $openingCommissions = Commissions::getCommissions(
            $agent,
            '1970-01-01',
            Carbon::createFromFormat('Y-m-d', $dateFrom)->addDays(-1)
                ->format('Y-m-d')
        );
        $openingCommissionValue = 0.00;
        foreach ($openingCommissions as $openingCommission) {
            $openingCommissionValue += $openingCommission->commission_value;
        }
        $openingCosts = Cost::where('agent', $agent)
            ->where('date', '<', $dateFrom)
            ->sum('value');
        $openingBalance = round($openingCommissionValue - $openingCosts, 2);

        // Calculate the current balance for this agent
        $balance = round(
            $openingBalance + $totalCommissions - $totalCosts,
            2
        );

        // Build complete response for this endpoint
        return response()->json(
            [
                'costs' => $costs,
                'costsCount' => $costsCount,
                'commissions' => $commissions,
                'commissionsCount' => count($commissions),
                'openingBalance' => $openingBalance,
                'agentTotals' => [
                    'costs' => floatval($totalCosts),
                    'commissions' => $totalCommissions,
                    'balance' => $balance,
                ],
                'deletePermission' =>
                    strpos($request->user->roles, 'Global Administrator') === false ?
                    false : true,
            ]
        );
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
                'date' => 'required|date_format:Y-m-d',
                'value' => 'required|numeric',
                'agent' => 'required|numeric',
                'customer' => 'numeric',
            ]
        );

        $cost = Cost::create(
            [
                'date' => $request->input('date', '1970-01-01'),
                'value' => $request->input('value', 0.00),
                'agent' => $request->input('agent', 0),
                'customer' => $request->input('customer', 0),
                'details' => $request->input('details', ''),
                'costType' => $request->input('type', Cost::TYPE_STANDARD),
            ]
        );

        return response()->json($cost);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id The cost id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param int                      $id      The cost id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id The cost id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cost::destroy($id);
        return response()->json(['status', 'success']);
    }
}
