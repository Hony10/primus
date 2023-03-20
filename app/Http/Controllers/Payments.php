<?php

/**
 * Payments File Doc Comment
 * 
 * PHP Version 8.0.3
 * 
 * @category PrimusDashboard
 * @package  PrimusDashboard_Payments
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://primusfs.co.uk/dashboard/
 */

namespace App\Http\Controllers;

use App\Models\LockedMonths;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Payments Controller Class
 *
 * @category PrimusDashboard
 * @package  PrimusDashboard_Payments
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://primusfs.co.uk/dashboard/
 */
class Payments extends Controller
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
        // We must have a customer id and date range for this endpoint
        $paymentsQuery = Payment::where('id', '>=', 0);

        $customer = $request->input('customer', false);
        $dateFrom = $request->input('dateFrom', false);
        $dateTo = $request->input('dateTo', false);

        if (!$customer || !$dateFrom || !$dateTo) {
            abort(400, 'Invalid data');
        }

        $paymentsQuery->where('customer', $customer)
            ->where('date', '>=', $dateFrom)
            ->where('date', '<=', $dateTo);
        
        $paymentsCount = $paymentsQuery->count();
        $payments = $paymentsQuery->get();

        // Get locked months in this date range
        $lockedMonths = LockedMonths::get();
            // ->where('month', '<=', $dateTo)
            // ->get();

        return response()->json(
            [
                'payments' => $payments,
                'lockedMonths' => $lockedMonths,
                'total' => $paymentsCount,
            ]
        );
    }

    /**
     * Lists all payments that match the specified criteria
     * 
     * @param \Illuminate\Http\Request $request The request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $paymentsQuery = Payment::where('payments.id', '>=', 0);

        if ($request->input('customer', false)) {
            $paymentsQuery->where('payments.customer', $request->input('customer'));
        }
        if ($request->input('dateFrom', false)) {
            $paymentsQuery->where(
                'payments.date',
                '>=',
                $request->input('dateFrom')
            );
        }
        if ($request->input('dateTo', false)) {
            $paymentsQuery->where('payments.date', '<=', $request->input('dateTo'));
        }
        if ($request->input('product', false)) {
            $paymentsQuery->where('payments.product', $request->input('product'));
        }
        if ($request->input('deal', false)) {
            $paymentsQuery->where('payments.deal', $request->input('deal'));
        }

        $paymentsQuery->leftJoin('deals', 'payments.deal', '=', 'deals.id');
        $paymentsQuery->leftJoin(
            'customers',
            'payments.customer',
            '=',
            'customers.id'
        );

        $payments = $paymentsQuery
            ->orderBy('payments.date', 'asc')
            ->orderBy('payments.product', 'asc')
            ->orderBy('payments.id', 'asc')
            ->select(
                [
                    'payments.*',
                    'deals.name AS deal_name',
                    'customers.name AS customer_name',
                ]
            )
            ->get();
        
        return response()->json($payments);
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
                'customer' => 'required',
                'date' => 'required',
                'product' => 'required',
                'deal' => 'required',
                'value' => 'required',
            ]
        );

        $payment = Payment::create(
            [
                'customer' => $request->input('customer', 0),
                'value' => $request->input('value', 0.00),
                'deal' => $request->input('deal', 0),
                'product' => $request->input('product', 0),
                'date' => $request->input('date', '1970-01-01'),
            ]
        );

        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param int                      $id      The payment ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::where('id', $id)->first();
        if (!$payment) {
            abort(404, 'Payment not found');
        }

        $payment->fill(
            [
                'customer' => $request->input('customer', $payment->customer),
                'value' => $request->input('value', $payment->value),
                'deal' => $request->input('deal', $payment->deal),
                'product' => $request->input('product', $payment->product),
                'date' => $request->input('date', $payment->date),
            ]
        );

        $payment->save();

        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id The payment ID
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Payment::destroy($id);
        return response()->json(['status', 'success']);
    }
}
