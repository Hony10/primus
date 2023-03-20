/**
 * Lock Months Refresh JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

var PageRefresh = function () {
    'use strict';

    /**
     * Refreshes the page list display
     */
    var refresh = function () {
        var requestData = {};

        // Get the default year (current year)
        var currentDate = new Date();
        requestData.year = currentDate.getFullYear();

        if (document.querySelector('[name=selected-year]').value !== '') {
            requestData.year = document.querySelector('[name=selected-year]').value;
        } else {
            document.querySelector('[name=selected-year]').value = requestData.year;
        }

        API.request({
            path: '/json/locked-months',
            method: 'get',
            cors: true,
            headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
            data: requestData,
            success: function (code, data) {
                if (code !== 200) {
                    PageDraw(false);
                    return;
                }
                PageDraw(data);
            },
            error: function () {
                PageDraw(false);
            }
        });
    };


    // Perform an initial refresh of the page
    refresh();

    // Return public execution
    return refresh;
}();