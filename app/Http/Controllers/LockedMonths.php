<?php

namespace App\Http\Controllers;

use App\Models\LockedMonths as ModelsLockedMonths;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LockedMonths extends Controller
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
     * @param \Illuminate\Http\Request $request The request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $month = $request->input('month', false);
        if (!$month) {
            abort(400, 'Invalid data');
        }

        $locked = ModelsLockedMonths::where('month', $month)->first();
        if ($locked) {
            return response()->json(['status' => 'success']);
        }

        $newEntry = ModelsLockedMonths::create(
            [
                'month' => $month,
            ]
        );

        if (!$newEntry) {
            abort(500, 'Failed to lock month');
        }

        // We need to loop through every payment for this month and lock on the deal
        // assignment
        $dateFrom = Carbon::createFromFormat('Y-m-d', $month);
        $dateTo = Carbon::createFromFormat('Y-m-d', $month)->endOfMonth();

        // Create the query to get all commissions and joined data
        $query = DB::table('payments')
            ->where('date', '>=', $dateFrom->format('Y-m-d'))
            ->where('date', '<=', $dateTo->format('Y-m-d'))
            ->join('customers', 'payments.customer', '=', 'customers.id')
            ->join('deals', 'customers.deal', '=', 'deals.id')
            ->orderBy('payments.date', 'asc')
            ->orderBy('customers.product', 'asc')
            ->orderBy('payments.id', 'asc')
            ->select(
                'payments.*',
                // 'customers.*',
                // 'deals.*',
                // 'customers.name as customer_name',
                // 'deals.name as deal_name'
                'deals.enabled',
                'deals.name',
                'deals.assignments',
                'deals.deduction',
                'deals.deduction_ivan',
            );

        // Get query result
        $result = $query->get();

        foreach ($result as $payment) {
            $assignmentData = $payment->assignments;
            $paymentObj = Payment::find($payment->id);
            $paymentObj->locked_assignments = $assignmentData;
            $paymentObj->locked_deduction = $payment->deduction;
            $paymentObj->locked_ivan_deduction = $payment->deduction_ivan;
            $paymentObj->save();
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request The request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $year = $request->input('year', Carbon::now()->format('Y'));

        $dateFrom = Carbon::create($year)->format('Y-m-d');
        $dateTo = Carbon::create($year, 12, 31)->format('Y-m-d');

        $lockedMonths = ModelsLockedMonths::where('month', '>=', $dateFrom)
            ->where('month', '<=', $dateTo)
            ->get();
        
        // Get any payments for each month we are loading
        $results = [];
        $searchDate = Carbon::createFromFormat('Y-m-d', $dateFrom);
        $searchEnd = Carbon::createFromFormat('Y-m-d', $dateTo);
        while ($searchDate->unix() < $searchEnd->unix()) {

            // Get the month lock state
            $monthLocked = false;
            foreach ($lockedMonths as $month) {
                if ($searchDate->format('Y-m-d') === $month->month) {
                    $monthLocked = true;
                }
            }

            // Get payment counts for the month
            $paymentCount = Payment::where(
                'date',
                '>=',
                $searchDate->format('Y-m-d')
            )->where(
                'date',
                '<=',
                $searchDate->copy()->endOfMonth()->format('Y-m-d')
            )->count();

            // Add to results
            $results[] = [
                'date' => $searchDate->format('Y-m-d'),
                'payments' => $paymentCount,
                'locked' => $monthLocked,
            ];

            // Increment the search date by 1 month
            $searchDate->addMonth();
        }

        return response()->json($results);
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
