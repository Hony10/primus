/**
 * Sub Form Selection JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

 (function() {
    'use strict';

    /**
     * Displays the correct form for the selected product on the page
     */
     var displaySubForm = function () {
        var productValue = '';
        document.querySelectorAll('[name=product]').forEach(function (element) {
            productValue = element.checked ? element.value : productValue;
        });

        document.querySelector('.product-form-mortgage').style.display = productValue === 'MORTGAGE' ? '' : 'none';
        document.querySelector('.product-form-life-insurance').style.display = productValue === 'LIFE INSURANCE' ? '' : 'none';
        document.querySelector('.product-b-and-c').style.display = productValue === 'B AND C' ? '' : 'none';
    };


    // Event handler to change the product form
    document.querySelectorAll('[name=product]').forEach(function (element) {
        element.addEventListener('change', displaySubForm);
    });

})();