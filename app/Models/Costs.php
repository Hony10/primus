<?php

/**
 * Costs Model File Doc Comment
 * 
 * PHP Version 8.0.3
 * 
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Costs Model File Doc Comment
 *
 * @category Primus
 * @package  PrimusDashboard
 * @author   James Plant <jamesplant@gmail.com>
 * @license  https://primusfs.co.uk UNLICENSED
 * @link     https://github.com/NetworkMonk/primus-dashboard/
 */
class Costs extends Model
{
    use HasFactory;

    const TYPE_STANDARD = 'standard';
    const TYPE_COMMISSION_PAYMENT = 'commission-payment';

    public $attributes = [
        'date' => '1970-01-01',
        'agent' => 0,
        'value' => 0.00,
        'details' => '',
        'customer' => 0,
        'costType' => '',
    ];

    public $fillable = [
        'date',
        'agent',
        'value',
        'details',
        'customer',
        'costType',
    ];
}
