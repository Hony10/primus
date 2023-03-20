<?php

/**
 * Commissions Model File Doc Comment
 * 
 * PHP Version 8.0.3
 * 
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Commissions Model File Doc Comment
 *
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class Commissions
{
    /**
     * Gets commissions for the criteria specified
     * 
     * @param int    $agent    The agent to query
     * @param string $dateFrom The date to get commissions from
     * @param string $dateTo   The date to get commissions to
     * @param mixed  $product  Optional product to filter commissions by
     * 
     * @return array
     */
    public static function getCommissions($agent, $dateFrom, $dateTo, $product=false)
    {
        // Get Ivan's ID
        $ivan = User::where('username', 'ivan@primusfs.co.uk')->first();


        // Get all input data
        $dateFrom = Carbon::createFromFormat('Y-m-d', $dateFrom);
        $dateTo = Carbon::createFromFormat('Y-m-d', $dateTo);


        // Create the query to get all commissions and joined data
        $query = DB::table('payments')
            ->where('date', '>=', $dateFrom->format('Y-m-d'))
            ->where('date', '<=', $dateTo->format('Y-m-d'))
            ->join('customers', 'payments.customer', '=', 'customers.id');

        // Add product filter if specified
        if ($product !== false) {
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
                if ($agent == $ivan->id) {
                    // Deductions
                    $ivanDeduction = floatval($payment->value) *
                        ($ivanDeductionData / 100);

                    // Assign agent commission
                    $paymentValue = 0.0;

                    // Add Ivan's deduction back in
                    $paymentValue += $ivanDeduction;

                    // Round this value
                    $payment->commission_value = round($paymentValue, 2);
                    

                    $finalResult[] = $payment;
                }
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
            if (($agentId === $ivan->id) || ($agent === $ivan->id)) {
                $paymentValue += $ivanDeduction;
            }

            // Round this value
            $payment->commission_value = round($paymentValue, 2);


            $finalResult[] = $payment;
        }

        return $finalResult;
    }

    /**
     * Gets commission total for the criteria specified
     * 
     * @param int   $agent   The agent to query
     * @param mixed $product Optional product to filter commissions by
     * 
     * @return float
     */
    public static function getCommissionTotal($agent, $product=false)
    {
        // Get Ivan's ID
        $ivan = User::where('username', 'ivan@primusfs.co.uk')->first();


        // Get all input data
        $product = $product;


        // Create the query to get all commissions and joined data
        $query = DB::table('payments')
            ->join('customers', 'payments.customer', '=', 'customers.id');

        // Add product filter if specified
        if ($product !== false) {
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
        $finalResult = 0.00;

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


            $finalResult += round($payment->commission_value, 2);
        }

        return round($finalResult, 2);
    }

}
