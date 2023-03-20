/**
 * Lock Months Modal JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

 (function () {
    'use strict';

    // Modal events
    document.querySelector('.modal-confirm').addEventListener('click', function () {
        var myModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));

        var requestData = { month: document.querySelector('.modal-date').value };

        API.request({
            path: '/json/locked-months',
            method: 'post',
            cors: true,
            headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
            data: requestData,
            always: PageRefresh,
        });
        myModal.hide();
    });

})();