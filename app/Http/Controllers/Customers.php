<?php

/**
 * Customers Controller File Doc Comment
 * 
 * PHP Version 8.0.3
 * 
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use Illuminate\Http\Request;

/**
 * Customers Controller Class
 *
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class Customers extends Controller
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
        // Start building a result
        $customersQuery = Customer::where('customers.id', '>=', 0);

        // We need to test if we are a global admin, if not only list customer that
        // have a deal assignment to the current user
        if (strpos($request->user->roles, 'Global Administrator') === false) {
            $reg = '"' . $request->user->id . '":\s?"[1-9]';
            $customersQuery->join('deals', 'customers.deal', '=', 'deals.id');
            $customersQuery->where('deals.assignments', 'REGEXP', $reg);
        }

        // Add any custom filters
        if ($request->input('product', false)) {
            $customersQuery->where(
                'customers.product',
                '=',
                $request->input('product')
            );
        }
        if ($request->input('deal', false)) {
            $customersQuery->where('customers.deal', '=', $request->input('deal'));
        }

        // Add full text search
        if ($searchStr = $request->input('search', false)) {
            $customersQuery->where(
                function ($query) use ($searchStr) {
                    $query->orWhere(
                        'customers.name',
                        'LIKE',
                        '%' . $searchStr . '%'
                    );
                    $query->orWhere(
                        'customers.mortgage_property',
                        'LIKE',
                        '%' . $searchStr . '%'
                    );
                }
            );
        }

        // Add custom sorting
        if ($request->input('order', false)) {
            $customersQuery->orderBy(
                $request->input('order'),
                $request->input('orderDir', 'asc')
            );
        } else {
            $customersQuery->orderBy('customers.name', 'asc');
        }

        // Get the total filtered count
        $customersCount = $customersQuery->count();

        if ($request->input('limit', 1) > 0) {
            // Limit by number
            $customersQuery->offset($request->input('offset', 0))
                ->limit($request->input('limit', 10));
        }


        // Get all results from query
        $customers = $customersQuery->select('customers.*')->get();

        return response()->json(
            [
                'customers' => $customers,
                'total' => $customersCount,
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
        // Validate input data
        $request->validate(
            [
                'status' => 'required',
                'name' => 'required',
                'deal' => 'required',
                'product' => 'required',
            ]
        );

        // Generate a slug for this customer account
        $slug = preg_replace(
            '/[^a-z0-9- ]/', '', strtolower($request->input('name'))
        );
        $slug = preg_replace('/[ ]+/', '-', $slug);
        
        // Check if there is another customer account with the same slug
        // If there is we want to append a number to the end of the slug
        $slugAppend = 0;
        $existingCustomer = Customer::where('slug', $slug)->first();
        while ($existingCustomer) {
            $slugAppend++;
            $slugTest = $slug . '-' .
                ($slugAppend < 1000 ? substr('000' . $slugAppend, -3) : $slugAppend);
            $existingCustomer = Customer::where('slug', $slugTest)->first();
            if (!$existingCustomer) {
                $slug = $slugTest;
            }
        }

        // Load the deal config and create a JSON string containing this data
        $dealConfig = '';
        $deal = Deal::where('id', $request->input('deal'))->first();
        if ($deal) {
            $dealConfig = json_encode($deal);
        }

        // Start creating the new customer record
        $customer = Customer::create(
            [
                'status' => $request->input('status'),
                
                'name' => $request->input('name'),
                'slug' => $slug,
                'mk' => $request->input('mk', ''),
                'deal' => $request->input('deal'),
                'deal_config' => $dealConfig,
                'product' => $request->input('product'),

                'mortgage_type' => $request->input('mortgage_type', ''),
                'mortgage_property' => $request->input('mortgage_property', ''),
                'mortgage_property_price' =>
                    $request->input('mortgage_property_price', 0),
                'mortgage_mortgage_required' =>
                    $request->input('mortgage_mortgage_required', 0),
                'mortgage_ltv' => $request->input('mortgage_ltv', 0),
                'mortgage_term' => $request->input('mortgage_term', 0),
                'mortgage_type_of_mortgage' =>
                    $request->input('mortgage_type_of_mortgage', ''),
                'mortgage_lender' => $request->input('mortgage_lender', 0),
                'mortgage_application_date' =>
                    $request->input('mortgage_application_date', '1970-01-01'),
                'mortgage_offer_date' =>
                    $request->input('mortgage_offer_date', '1970-01-01'),
                'mortgage_completion_date' =>
                    $request->input('mortgage_completion_date', '1970-01-01'),
                'mortgage_lg_recon_date' =>
                    $request->input('mortgage_lg_recon_date', '1970-01-01'),
                'mortgage_lg_reference' =>
                    $request->input('mortgage_lg_reference', ''),
        
                'life_lender_product' =>
                    $request->input('life_lender_product', ''),
                'life_type' => $request->input('life_type', ''),
                'life_single_joint' => $request->input('life_single_joint', ''),
                'life_ci' => $request->input('life_ci', ''),
                'life_waiver' => $request->input('life_waiver', ''),
                'life_life_cover' => $request->input('life_life_cover', ''),
                'life_ci_cover' => $request->input('life_ci_cover', ''),
                'life_term' => $request->input('life_term', 0),
                'life_application_date' =>
                    $request->input('life_application_date', '1970-01-01'),
                'life_start_date' => $request->input(
                    'life_start_date', '1970-01-01'
                ),
                'life_end_date' => $request->input('life_end_date', '1970-01-01'),
                'life_premium' => $request->input('life_premium', 0),
                'life_lapsed_date' => $request->input(
                    'life_lapsed_date', '1970-01-01'
                ),
                'life_policy_number' => $request->input('life_policy_number', ''),
                'life_indem_comm' => $request->input('life_indem_comm', ''),
                'life_indem_paid_date' =>
                    $request->input('life_indem_paid_date', ''),
        
                'bandc_provider' => $request->input('bandc_provider', ''),
                'bandc_property_postcode' =>
                    $request->input('bandc_property_postcode', ''),
                'bandc_policy_number' => $request->input('bandc_policy_number', ''),
                'bandc_resi_let' => $request->input('bandc_resi_let', ''),
                'bandc_current' => $request->input('bandc_current', ''),
                'bandc_dd_ann' => $request->input('bandc_dd_ann', ''),
                'bandc_start_date' => $request->input(
                    'bandc_start_date', '1970-01-01'
                ),
                'bandc_taken_up' => $request->input('bandc_taken_up', ''),
            ]
        );

        if (!$customer) {
            abort(400, 'Failed to create customer account, a server error occurred');
        }

        return response()->json(['status' => 'success', 'slug' => $slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param string                   $slug    Customer slug
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $customerQuery = Customer::where('customers.slug', $slug);
        
        // We need to test if we are a global admin, if not only list customer that
        // have a deal assignment to the current user
        if (strpos($request->user->roles, 'Global Administrator') === false) {
            $reg = '"' . $request->user->id . '":\s?"[1-9]';
            $customerQuery->join('deals', 'customers.deal', '=', 'deals.id');
            $customerQuery->where('deals.assignments', 'REGEXP', $reg);
        }

        $customer = $customerQuery->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request  The request object
     * @param string                   $customer Customer slug
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $customer)
    {
        $customer = Customer::where('slug', $customer)->first();
        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Load the deal config and create a JSON string containing this data
        $dealConfig = '';
        $deal = Deal::where('id', $request->input('deal', $customer->deal))->first();
        if ($deal) {
            $dealConfig = json_encode($deal);
        }

        $customer->fill(
            [
                'status' => $request->input('status', $customer->status),
                
                'name' => $request->input('name', $customer->name),
                'mk' => $request->input('mk', $customer->mk),
                'deal' => $request->input('deal', $customer->deal),
                'deal_config' => $dealConfig,
                'product' => $request->input('product', $customer->product),

                'mortgage_type' => $request->input(
                    'mortgage_type', $customer->mortgage_type
                ),
                'mortgage_property' => $request->input(
                    'mortgage_property', $customer->mortgage_property
                ),
                'mortgage_property_price' => $request->input(
                    'mortgage_property_price',
                    $customer->mortgage_property_price
                ),
                'mortgage_mortgage_required' =>
                    $request->input('mortgage_mortgage_required', 0),
                'mortgage_ltv' => $request->input('mortgage_ltv', 0),
                'mortgage_term' => $request->input('mortgage_term', 0),
                'mortgage_type_of_mortgage' =>
                    $request->input('mortgage_type_of_mortgage', ''),
                'mortgage_lender' => $request->input('mortgage_lender', 0),
                'mortgage_application_date' =>
                    $request->input('mortgage_application_date', '1970-01-01'),
                'mortgage_offer_date' =>
                    $request->input('mortgage_offer_date', '1970-01-01'),
                'mortgage_completion_date' =>
                    $request->input('mortgage_completion_date', '1970-01-01'),
                'mortgage_lg_recon_date' =>
                    $request->input('mortgage_lg_recon_date', '1970-01-01'),
                'mortgage_lg_reference' =>
                    $request->input('mortgage_lg_reference', ''),
        
                'life_lender_product' =>
                    $request->input('life_lender_product', ''),
                'life_type' => $request->input('life_type', ''),
                'life_single_joint' => $request->input('life_single_joint', ''),
                'life_ci' => $request->input('life_ci', ''),
                'life_waiver' => $request->input('life_waiver', ''),
                'life_life_cover' => $request->input('life_life_cover', ''),
                'life_ci_cover' => $request->input('life_ci_cover', ''),
                'life_term' => $request->input('life_term', 0),
                'life_application_date' =>
                    $request->input('life_application_date', '1970-01-01'),
                'life_start_date' => $request->input(
                    'life_start_date', '1970-01-01'
                ),
                'life_end_date' => $request->input('life_end_date', '1970-01-01'),
                'life_premium' => $request->input('life_premium', 0),
                'life_lapsed_date' => $request->input(
                    'life_lapsed_date', '1970-01-01'
                ),
                'life_policy_number' => $request->input('life_policy_number', ''),
                'life_indem_comm' => $request->input('life_indem_comm', ''),
                'life_indem_paid_date' =>
                    $request->input('life_indem_paid_date', ''),
        
                'bandc_provider' => $request->input('bandc_provider', ''),
                'bandc_property_postcode' =>
                    $request->input('bandc_property_postcode', ''),
                'bandc_policy_number' => $request->input('bandc_policy_number', ''),
                'bandc_resi_let' => $request->input('bandc_resi_let', ''),
                'bandc_current' => $request->input('bandc_current', ''),
                'bandc_dd_ann' => $request->input('bandc_dd_ann', ''),
                'bandc_start_date' => $request->input(
                    'bandc_start_date', '1970-01-01'
                ),
                'bandc_taken_up' => $request->input('bandc_taken_up', ''),
            ]
        );

        $customer->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Customer id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
