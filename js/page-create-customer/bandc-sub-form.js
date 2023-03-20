/**
 * B and C Sub Form JavaScript Document.
 * 
 * @author James Plant <james.plant@whitestores.co.uk>
 * @since  1.0.0
 */

(function () {
    'use strict';

    /**
     * Determines if the other provider entry field should be visible or not
     */
    var displayOtherProvider = function () {
        document.querySelector('.bandc-provider-other-container').style.display =
            document.querySelector('[name=bandc-provider]').value === 'other' ? '' : 'none';
    };

    
    // Event handlers for the B and C sub form
    document.querySelector('[name=bandc-provider]').addEventListener('change', displayOtherProvider);

})();
