<?php

/**
 * Customer Migration File Doc Comment
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
use Illuminate\Support\Facades\Schema;

/**
 * Customer Migration Class
 *
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'customers', function (Blueprint $table) {
                $table->id();

                $table->string('product', 100)->index();
                $table->integer('deal')->index();
                $table->string('name', 100)->index();
                $table->string('type', 100)->index();

                $table->double('property_price');
                $table->double('mortgage_required');
                $table->string('mortgage_type', 100);
                
                $table->integer('term');

                $table->string('lender', 100);

                $table->date('mortgage_date_application');
                $table->date('mortgage_date_offer');
                $table->date('mortgage_date_completed');
                $table->date('mortgage_lg_recon_date');
                $table->string('mortgage_lg_ref', 100);
                $table->double('mortgage_clawback_value');
                $table->date('mortgage_clawback_date');

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
