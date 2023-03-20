/**
 * Create customer deal selection JavaScript document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

 (function () {
    'use strict';

    var deals = [];


    /**
     * Loads all current deals
     * @returns Promise
     */
    var load = function () {
        return new Promise(function (resolve) {
            API.request({
                path: '/json/deals',
                method: 'get',
                cors: true,
                headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                success: function (code, data) {
                    if (code !== 200) {
                        return;
                    }
                    deals = data;
                },
                always: resolve,
            });
        });
    };

    /**
     * Displays all loaded deal data
     * @param {array} deals Contains all deal data
     */
    var display = function (deals) {
        try {
            deals = deals.sort(function (a, b) {
                return a.name.toLowerCase() < b.name.toLowerCase() ? -1 : (
                    a.name.toLowerCase() > b.name.toLowerCase() ? 1 : 0
                );
            });


            document.querySelector('.deal-selection-container').innerHTML = deals.map(function (deal) {
                return '<div class="col-12 col-md-4">' +
                    '<label style="cursor: pointer;">' +
                        '<input class="form-check-input" type="radio" name="deal" value="' + deal.id + '" style="vertical-align: top; margin-top: 10px;" ' +
                            (document.querySelector('.deal-loaded') && (parseInt(document.querySelector('.deal-loaded').value) === deal.id) ? ' checked="checked"' : '') +
                        '/>' +
                        '<div class="ms-3 me-3 rounded-circle d-inline-block" style="' +
                            'background-color: ' + deal.colour + '; ' +
                            'min-height: 32px; ' +
                            'min-width: 32px; ' +
                            'overflow: hidden; ' +
                        '"></div>' +
                        '<h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">' +
                            deal.name +
                        '</h4>' +
                    '</label>' +
                '</div>';
            }).join('');
        } catch (e) {
            console.error(e.message, e.stack);
        }
    };

    var updateSelectedDeal = function (element) {
        console.log(element);

        var newId = element.getAttribute('data-deal-id');
        newId = isNaN(parseInt(newId)) ? 0 : parseInt(newId);

        document.querySelector('.deal-loaded').value = newId;

        var selectedDeal = deals.find(function (deal) { return deal.id === newId; });

        if (selectedDeal) {

            document.querySelector('.deal-selected-preview').innerHTML = '' +
                '<div class="me-3 rounded-circle d-inline-block" style="background-color: ' + selectedDeal.colour + '; min-height: 32px; min-width: 32px; overflow: hidden;"></div>' +
                '<h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">' + selectedDeal.name + '</h4>';

            return;
        }

        document.querySelector('.deal-selected-preview').innerHTML = '<span class="alert alert-danger">' +
            '<i class="fe fe-alert-triangle me-3"></i>' +
            'Unknown Deal Selected' +
        '</span>';
    };


    // Event handler for when a deal is selected
    if (document.querySelector('.deal-dropdown-menu')) {
        document.querySelectorAll('.deal-dropdown-menu a').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                updateSelectedDeal(this);
            });
        });
    }


    // Load all deals available
    load();

})();