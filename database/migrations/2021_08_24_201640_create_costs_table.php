<?php

/**
 * Costs migration File Doc Comment
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
 * Costs migration File Doc Comment
 *
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'costs',
            function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->date('date')->index();
                $table->integer('agent')->index();
                $table->decimal('value', 8, 2)->index();
                $table->string('details', 250);
                $table->integer('customer')->index();
                $table->string('costType')->index();
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
        Schema::dropIfExists('costs');
    }
}
