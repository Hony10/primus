<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentAssignments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'payments', function (Blueprint $table) {
                $table->text('locked_assignments');
                $table->integer('locked_deduction')->default(0);
                $table->integer('locked_ivan_deduction')->default(0);
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
        Schema::table(
            'payments', function (Blueprint $table) {
                $table->dropColumn('locked_assignments');
                $table->dropColumn('locked_deduction');
                $table->dropColumn('locked_ivan_deduction');
            }
        );
    }
}
