<?php

/**
 * Payments migration File Doc Comment
 * 
 * PHP Version 8.0.3
 * 
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Payments migration File Doc Comment
 *
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'payments',
            function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->integer('customer')->index();
                $table->decimal('value', 8, 2);
                $table->integer('deal')->index();
                $table->string('product', 150)->index();
                $table->date('date')->index();
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
        Schema::dropIfExists('payments');
    }
}
