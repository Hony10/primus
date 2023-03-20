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
class Payment extends Model
{
    use HasFactory;

    public $attributes = [
        'customer' => 0,
        'value' => 0.00,
        'deal' => 0,
        'product' => '',
        'date' => '1970-01-01',
        'locked_assignments' => '',
    ];

    public $fillable = [
        'customer',
        'value',
        'deal',
        'product',
        'date',
        'locked_assignments',
    ];
}
