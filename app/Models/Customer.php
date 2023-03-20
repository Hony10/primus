<?php

/**
 * Customer Model File Doc Comment
 * 
 * PHP Version 8.0.2
 * 
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Customer Model Class
 *
 * @category Primus
 * @package  Primus_Dashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class Customer extends Model
{
    use HasFactory;

    protected $attributes = [

        'status' => '',

        'name' => '',
        'slug' => '',
        'mk' => '',
        'deal' => 0,
        'deal_config' => '',
        'product' => '',

        'mortgage_type' => '',
        'mortgage_property' => '',
        'mortgage_property_price' => 0,
        'mortgage_mortgage_required' => 0,
        'mortgage_ltv' => 0,
        'mortgage_term' => 0,
        'mortgage_type_of_mortgage' => '',
        'mortgage_lender' => '',
        'mortgage_application_date' => '0000-00-00',
        'mortgage_offer_date' => '0000-00-00',
        'mortgage_completion_date' => '0000-00-00',
        'mortgage_lg_recon_date' => '0000-00-00',
        'mortgage_lg_reference' => '',

        'life_lender_product' => '',
        'life_type' => '',
        'life_single_joint' => '',
        'life_ci' => '',
        'life_waiver' => '',
        'life_life_cover' => '',
        'life_ci_cover' => '',
        'life_term' => 0,
        'life_application_date' => '0000-00-00',
        'life_start_date' => '0000-00-00',
        'life_end_date' => '0000-00-00',
        'life_premium' => 0,
        'life_lapsed_date' => '0000-00-00',
        'life_policy_number' => '',
        'life_indem_comm' => '',
        'life_indem_paid_date' => '',

        'bandc_provider' => '',
        'bandc_property_postcode' => '',
        'bandc_policy_number' => '',
        'bandc_resi_let' => '',
        'bandc_current' => '',
        'bandc_dd_ann' => '',
        'bandc_start_date' => '0000-00-00',
        'bandc_taken_up' => '',
    ];

    protected $fillable = [

        'status',

        'name',
        'slug',
        'mk',
        'deal',
        'deal_config',
        'product',

        'mortgage_type',
        'mortgage_property',
        'mortgage_property_price',
        'mortgage_mortgage_required',
        'mortgage_ltv',
        'mortgage_term',
        'mortgage_type_of_mortgage',
        'mortgage_lender',
        'mortgage_application_date',
        'mortgage_offer_date',
        'mortgage_completion_date',
        'mortgage_lg_recon_date',
        'mortgage_lg_reference',

        'life_lender_product',
        'life_type',
        'life_single_joint',
        'life_ci',
        'life_waiver',
        'life_life_cover',
        'life_ci_cover',
        'life_term',
        'life_application_date',
        'life_start_date',
        'life_end_date',
        'life_premium',
        'life_lapsed_date',
        'life_policy_number',
        'life_indem_comm',
        'life_indem_paid_date',

        'bandc_provider',
        'bandc_property_postcode',
        'bandc_policy_number',
        'bandc_resi_let',
        'bandc_current',
        'bandc_dd_ann',
        'bandc_start_date',
        'bandc_taken_up',
    ];
}
