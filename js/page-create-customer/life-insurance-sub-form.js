/**
 * Life Insurance Sub Form JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

(function () {
    'use strict';

    /**
     * Determines if the other lender product entry field should be visible or not
     */
    var displayOtherProduct = function () {
        document.querySelector('.life-lender-other-container').style.display = 
            document.querySelector('[name=life-lender-product]').value === 'other' ? '' : 'none';
    };

    
    // Event handlers for the life insurance sub form
    document.querySelector('[name=life-lender-product]').addEventListener('change', displayOtherProduct);

})();
