<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Commissions extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param \Illuminate\Http\Request $request The request object
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validate input data
        $request->validate(
            [
                'dateFrom' => 'required',
                'dateTo' => 'required',
                'agent' => 'required'
            ]
        );


        // Get Ivan's ID
        $ivan = User::where('username', 'ivan@primusfs.co.uk')->first();


        // Get all input data
        $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'));
        $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'));
        $product = $request->input('product', false);
        $agent = intval($request->input('agent'));


        // Create the query to get all commissions and joined data
        $query = DB::table('payments')
            ->where('date', '>=', $dateFrom->format('Y-m-d'))
            ->where('date', '<=', $dateTo->format('Y-m-d'))
            ->join('customers', 'payments.customer', '=', 'customers.id');

        // Add product filter if specified
        if ($product !== '') {
            $query->where('customers.product', '=', $product);
        }

        $query->join('deals', 'customers.deal', '=', 'deals.id')
            ->orderBy('payments.date', 'asc')
            ->orderBy('customers.product', 'asc')
            ->orderBy('payments.id', 'asc')
            ->select(
                'payments.*',
                'customers.*',
                'deals.*',
                'customers.name as customer_name',
                'deals.name as deal_name'
            );


        // Get query result
        $result = $query->get();
        $finalResult = [];

        // Loop through each payment and see if this applies to this agent
        foreach ($result as $payment) {
            $assignmentData = json_decode($payment->assignments);
            $deductionData = $payment->deduction;
            $ivanDeductionData = $payment->deduction_ivan;

            if ($payment->locked_assignments !== '') {
                $assignmentData = json_decode($payment->locked_assignments);
                $deductionData = $payment->locked_deduction;
                $ivanDeductionData = $payment->locked_ivan_deduction;
            }

            $agentAssigned = false;
            $agentCommission = 0;
            $agentId = 0;
            foreach ($assignmentData as $key => $val) {
                if (($agent == intval($key)) && (intval($val) > 0)) {
                    $agentAssigned = true;
                    $agentCommission = $val;
                    $agentId = intval($key);
                }
            }

            if (!$agentAssigned) {
                continue;
            }


            // Add the commission value for this agent
            $paymentValue = floatval($payment->value);

            // Deductions
            $paymentValue -= floatval($payment->value) *
                ($deductionData / 100);
            $ivanDeduction = floatval($payment->value) *
                ($ivanDeductionData / 100);
            $paymentValue -= $ivanDeduction;

            // Assign agent commission
            $paymentValue = $paymentValue * ($agentCommission / 100);

            // Add Ivan's deduction back in if this agent is Ivan
            if ($agentId === $ivan->id) {
                $paymentValue += $ivanDeduction;
            }

            // Round this value
            $payment->commission_value = round($paymentValue, 2);


            $finalResult[] = $payment;
        }

        return response()->json($finalResult);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
