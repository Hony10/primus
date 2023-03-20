<?php

/**
 * Web Routes File Doc Comment
 * 
 * PHP Version 8.0.2
 * 
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */

use App\Models\Deal;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// No authentication required
// Auth pages
Route::get(
    '/sign-in',
    function () {
        return view('pages.sign-in');
    }
);
Route::post('/sign-in', 'App\Http\Controllers\Auth@store');

// Authentication required - no roles
Route::group(
    ['middleware' => ['view.authenticated']], function () {
        Route::get(
            '/',
            function () {
                return view('pages.home');
            }
        );

        // Sign out endpoint
        Route::delete('/sign-in', 'App\Http\Controllers\Auth@destroy');

        // Users endpoints
        Route::get('/json/users', 'App\Http\Controllers\Users@index');
        Route::get('/json/users/{username}', 'App\Http\Controllers\Users@show');

        // Deal endpoints
        Route::get('/json/deals', 'App\Http\Controllers\Deals@index');
        Route::get('/json/deals/{deal}', 'App\Http\Controllers\Deals@show');
    }
);

// Authentication required - Global Administrator or Agent
Route::group(
    [
        'middleware' => ['view.authenticated:Global Administrator|Agent']
    ], function () {

        // Customer pages
        Route::get(
            '/customers/create',
            function () {
                return view('pages.customers.create');
            }
        );
        Route::get(
            '/customers',
            function () {
                return view('pages.customers.dashboard');
            }
        );
        Route::get(
            '/customers/{customer}',
            function (Request $request, $customer) {
                $customerQuery = \App\Models\Customer::where(
                    'customers.slug',
                    $customer
                );

                $userRoles = $request->user->roles;
                $role = 'Global Administrator';

                // We need to test if we are a global admin, if not only show
                // customer if current user is assigned
                if (strpos($userRoles, $role) === false) {
                    $reg = '"' . $request->user->id . '":\s?"[1-9]';
                    $customerQuery->join('deals', 'customers.deal', '=', 'deals.id');
                    $customerQuery->where('deals.assignments', 'REGEXP', $reg);
                }

                $customer = $customerQuery->first();
                if (!$customer) {
                    abort(404, 'Customer not found');
                }

                return view('pages.customers.edit')->with('customer', $customer);
            }
        );

        // Customer management JSON endpoints
        Route::get('/json/customers', 'App\Http\Controllers\Customers@index');
        Route::get('/json/customers/{slug}', 'App\Http\Controllers\Customers@show');
        Route::post('/json/customers', 'App\Http\Controllers\Customers@store');
        Route::put(
            '/json/customers/{slug}', 'App\Http\Controllers\Customers@update'
        );




        // Commissions views
        Route::get(
            '/commissions',
            function () {
                return view('pages.commissions.commissions');
            }
        );

        // Commissions JSON endpoints
        Route::get('/json/commissions', 'App\Http\Controllers\Commissions@index');




        // Costs views
        Route::get(
            '/agent',
            function () {
                return view('pages.agent.agent');
            }
        );

        // Costs JSON endpoints
        Route::get('/json/costs', 'App\Http\Controllers\Costs@index');
        Route::post('/json/costs', 'App\Http\Controllers\Costs@store');
        Route::delete('/json/costs/{id}', 'App\Http\Controllers\Costs@destroy');

    }
);

// Authentication required - Global Administrator
Route::group(
    ['middleware' => ['view.authenticated:Global Administrator']], function () {
        // User management
        Route::get(
            '/users/create',
            function () {
                return view('pages.users.create');
            }
        );
        Route::get(
            '/users',
            function () {
                return view('pages.users.list');
            }
        );
        Route::get(
            '/users/{username}',
            function ($username) {
                return view('pages.users.edit')->with('username', $username);
            }
        );

        // User management JSON endpoints
        Route::post('/user', 'App\Http\Controllers\CreateUser@store');
        Route::put('/json/users/{username}', 'App\Http\Controllers\Users@update');

        // Deal management
        Route::get(
            '/deals',
            function () {
                return view('pages.deals.list');
            }
        );
        Route::get(
            '/deals/create',
            function () {
                return view('pages.deals.create');
            }
        );
        Route::get(
            '/deals/{deal}',
            function ($deal) {
                $dealObj = Deal::where('slug', $deal)->first();
                return view('pages.deals.edit')->with('deal', $dealObj->name)
                    ->with('id', $dealObj->id)->with('slug', $deal);
            }
        );

        // Deal management JSON endpoints
        Route::put('/json/deals/{deal}', 'App\Http\Controllers\Deals@update');
        Route::post('/json/deals', 'App\Http\Controllers\Deals@store');
        Route::delete('/json/deals/{deal}', 'App\Http\Controllers\Deals@destroy');


        
        // Payment entry views
        Route::get(
            '/payments',
            function () {
                return view('pages.payments.entry');
            }
        );

        // Payment JSON endpoints
        Route::get('/json/payments', 'App\Http\Controllers\Payments@index');
        Route::get('/json/payments/statement', 'App\Http\Controllers\Payments@list');
        Route::post('/json/payments', 'App\Http\Controllers\Payments@store');
        Route::put('/json/payments/{id}', 'App\Http\Controllers\Payments@update');
        Route::delete(
            '/json/payments/{id}',
            'App\Http\Controllers\Payments@destroy'
        );


        
        // Statements views
        Route::get(
            '/statements',
            function () {
                return view('pages.statements.statement');
            }
        );



        // Lock months view
        Route::get(
            '/admin/lock-months',
            function () {
                return view('pages.admin.lock-months');
            }
        );

        // Lock months JSON endpoints
        Route::get('/json/locked-months', 'App\Http\Controllers\LockedMonths@show');
        Route::post(
            '/json/locked-months',
            'App\Http\Controllers\LockedMonths@store'
        );




        // End of Month
        Route::get(
            '/end-of-month',
            function () {
                return view('pages.end-of-month.end-of-month');
            }
        );
        Route::get('/json/end-of-month', 'App\Http\Controllers\EndOfMonth@show');
        Route::get('/json/end-of-month/download', 'App\Http\Controllers\EndOfMonthDownload@show');
    }
);



