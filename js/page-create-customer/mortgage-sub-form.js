/**
 * Mortgage Sub Form JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

(function() {
    'use strict';

    /**
     * Calculates and displays the mortgage LTV
     */
     var calcLTV = function () {
        var price = document.querySelector('[name=mortgage-property-price]').value;
        var mortgage = document.querySelector('[name=mortgage-mortgage-required]').value;

        price = isNaN(parseFloat(price)) ? 0 : parseFloat(price);
        mortgage = isNaN(parseFloat(mortgage)) ? 0 : parseFloat(mortgage);

        document.querySelector('[name=mortgage-ltv]').value = price > 0 ? ((mortgage / price) * 100).toFixed(2) : '';
    };

    // Event handlers where the mortgage LTV needs to be recalculated
    document.querySelector('[name=mortgage-property-price]').addEventListener('change', calcLTV);
    document.querySelector('[name=mortgage-property-price]').addEventListener('keyup', calcLTV);
    document.querySelector('[name=mortgage-mortgage-required]').addEventListener('change', calcLTV);
    document.querySelector('[name=mortgage-mortgage-required]').addEventListener('keyup', calcLTV);
    
})();