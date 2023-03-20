(function () {
    'use strict';
    

    var deals = [];


    /**
     * Loads all current deals
     * @returns Promise
     */
    var load = function () {
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
        });
    };


    // Create event handler for selecting a deal
    document.querySelectorAll('.deal-selected-preview .dropdown-item').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            document.querySelector('[name=filter-deal]').value = element.getAttribute('data-deal-id') === '*' ? '*' : parseInt(element.getAttribute('data-deal-id'));
            Statements.refresh();

            if (element.getAttribute('data-deal-id') === '*') {
                document.querySelector('.deal-selected-preview > span').innerHTML =
                    '<h2 class="m-0"><span class="badge border text-dark" style="background-color: #fff;">' +
                        'All Deals' +
                    '</span></h2>';
                return;
            }

            var selectedDeal = deals.find(function (deal) { return deal.id === parseInt(element.getAttribute('data-deal-id')); });

            // Create the deal preview
            document.querySelector('.deal-selected-preview > span').innerHTML = typeof(selectedDeal) === 'undefined' ? (
                'No Deal Selected'
            ) : (
                '<h2 class="m-0"><span class="badge" style="background-color: ' + selectedDeal.colour + ';">' +
                    selectedDeal.name +
                '</span></h2>'
            );
        });
    });


    load();

})();