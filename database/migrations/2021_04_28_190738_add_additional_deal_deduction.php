<?php

/**
 * Migration File Doc Comment
 * 
 * PHP Version 8.0.2
 * 
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


/**
 * Migration Class
 *
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class AddAdditionalDealDeduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'deals', function (Blueprint $table) {
                $table->integer('deduction_ivan');
            }
        );

        // Set default value for new columns
        DB::raw('UPDATE deals SET deduction_ivan=0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'deals', function (Blueprint $table) {
                $table->dropColumn('deduction_ivan');
            }
        );
    }
}
