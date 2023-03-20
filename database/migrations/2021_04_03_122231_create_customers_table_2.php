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
class CreateCustomersTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customers');
        Schema::create(
            'customers',
            function (Blueprint $table) {
                $table->id();
                $table->timestamps();

                $table->string('status', 200)->index();

                $table->string('name', 200)->index();
                $table->string('slug', 200)->index();
                $table->string('mk', 100)->index();
                $table->integer('deal')->index();
                $table->text('deal_config');
                $table->string('product', 100)->index();

                $table->string('mortgage_type', 200)->index();
                $table->double('mortgage_property_price');
                $table->double('mortgage_mortgage_required');
                $table->double('mortgage_ltv');
                $table->integer('mortgage_term');
                $table->string('mortgage_type_of_mortgage', 200);
                $table->string('mortgage_lender', 200)->index();
                $table->date('mortgage_application_date');
                $table->date('mortgage_offer_date');
                $table->date('mortgage_completion_date');
                $table->date('mortgage_lg_recon_date');
                $table->string('mortgage_lg_reference', 200);

                $table->string('life_lender_product', 200)->index();
                $table->string('life_type', 200)->index();
                $table->string('life_single_joint', 200);
                $table->string('life_ci', 200);
                $table->string('life_waiver', 200);
                $table->string('life_life_cover', 200);
                $table->string('life_ci_cover', 200);
                $table->integer('life_term');
                $table->date('life_application_date');
                $table->date('life_start_date');
                $table->date('life_end_date');
                $table->double('life_premium');
                $table->date('life_lapsed_date');
                $table->string('life_policy_number', 200);
                $table->string('life_indem_comm', 200);
                $table->string('life_indem_paid_date', 200);

                $table->string('bandc_provider', 200)->index();
                $table->string('bandc_property_postcode', 200);
                $table->string('bandc_policy_number', 200);
                $table->string('bandc_resi_let', 200);
                $table->string('bandc_current', 200);
                $table->string('bandc_dd_ann', 200);
                $table->date('bandc_start_date');
                $table->string('bandc_taken_up', 100);
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
        Schema::dropIfExists('customers_table_2');
    }
}
