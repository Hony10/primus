<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EndOfMonthDownload extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request The request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Get Ivan's ID
        $ivan = User::where('username', 'ivan@primusfs.co.uk')->first();

        // Get the input date and convert to a carbon date object
        $month = $request->input('month', date('M Y'));
        $month = Carbon::createFromFormat('M Y', $month);
        $month->setDay(1);
        $month->setTime(0, 0);

        // Get a list of all current deals
        $deals = Deal::where('id', $request->input('deal', 0))->get();

        $payments = [];

        // Get the totals for each deal
        foreach ($deals as $deal) {
            // Get total payment values
            $deal->lifeTotal = Payment::where('payments.deal', $deal->id)
                ->where('payments.date', '<=', $month->format('Y-m-d'))
                ->where('payments.date', '>=', $month->format('Y-m-d'))
                ->where('payments.product', 'LIFE INSURANCE')
                ->sum('value');

            $deal->bcTotal = Payment::where('payments.deal', $deal->id)
                ->where('payments.date', '<=', $month->format('Y-m-d'))
                ->where('payments.date', '>=', $month->format('Y-m-d'))
                ->where('payments.product', 'B AND C')
                ->sum('value');

            $deal->mortgageTotal = Payment::where('payments.deal', $deal->id)
                ->where('payments.date', '<=', $month->format('Y-m-d'))
                ->where('payments.date', '>=', $month->format('Y-m-d'))
                ->where('payments.product', 'MORTGAGE')
                ->sum('value');

            $deal->jlmTotal = round(
                $deal->lifeTotal + $deal->bcTotal + $deal->mortgageTotal,
                2
            );

            $deal->ivanDeductions = 0.0;
            $deal->jlmDeductions = 0.0;
            $deal->ivanReceives = 0.0;
            $deal->ivanCommissions = 0.0;


            // Create the query to get all commissions and joined data
            $query = DB::table('payments')
                ->where('date', '>=', $month->format('Y-m-d'))
                ->where('date', '<=', $month->format('Y-m-d'))
                ->join('customers', 'payments.customer', '=', 'customers.id');

            $query->join('deals', 'customers.deal', '=', 'deals.id')
                ->orderBy('payments.date', 'asc')
                ->orderBy('customers.product', 'asc')
                ->orderBy('payments.id', 'asc')
                ->where('deals.id', $deal->id)
                ->select(
                    'payments.*',
                    'customers.*',
                    'deals.*',
                    'customers.name as customer_name',
                    'deals.name as deal_name'
                );


            // Get query result
            $result = $query->get();

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

                $agentCommission = 0;
                foreach ($assignmentData as $key => $val) {
                    if (($ivan->id == intval($key)) && (intval($val) > 0)) {
                        $agentCommission = $val;
                    }
                }

                // Add the commission value for this agent
                $paymentValue = floatval($payment->value);

                // Deductions
                $paymentValue -= floatval($payment->value) *
                    ($deductionData / 100);
                $ivanDeduction = floatval($payment->value) *
                    ($ivanDeductionData / 100);
                $paymentValue -= $ivanDeduction;

                $jlmDeduction = round(
                    floatval($payment->value) *
                    ($deductionData / 100),
                    2
                );
                $deal->ivanDeductions += round(floatval($ivanDeduction), 2);

                // Assign agent commission
                $paymentValue = $paymentValue * ($agentCommission / 100);
                $deal->ivanCommissions += round($paymentValue, 2);
                $deal->ivanReceives += round($paymentValue + $ivanDeduction, 2);

                $payments[] = [
                    $payment->customer_name,
                    $payment->product === 'LIFE INSURANCE' ? round(floatval($payment->value), 2) : 0,
                    $payment->product === 'B AND C' ? round(floatval($payment->value), 2) : 0,
                    $payment->product === 'MORTGAGE' ? round(floatval($payment->value), 2) : 0,
                    round(floatval($jlmDeduction), 2),
                    round(floatval($payment->value - $jlmDeduction), 2),
                    round(floatval($ivanDeduction), 2),
                    round(floatval($paymentValue), 2),
                    round(floatval($ivanDeduction + $paymentValue), 2)
                ];
            }
        }

        $headers = array(
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="end-of-month.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'expires' => '0'
        );

        $callback = function() use ($payments)
        {
            $file = fopen('php://output', 'w');

            fputcsv(
                $file,
                [
                    'Customer',
                    'Life Insurance',
                    'B And C',
                    'Mortgage',
                    'JLM Commission',
                    'Total After Deductions',
                    'Ivan Handling Fees',
                    'Ivan Commission',
                    'Ivan Receives',
                ]
            );

            foreach ($payments as $payment) {
                fputcsv($file, $payment);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

        // return response()->json(
        //     [
        //         'month' => $month,
        //         'deals' => $deals,
        //     ]
        // );
        // $request->validate('month', 'required');
    }
}
