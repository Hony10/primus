/**
 * Customer dashboard main JavaScript document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

(function () {
    'use strict';

    // Stores a reference to a timeout that will execute a load on the current page
    var loadBounce = false;

    // How long do we want to wait before we execute the load on the current page
    var loadBounceDelay = 500;

    // Stores objects locally when the page is loaded, such as deal details and agent details
    var cache = [];

    var currentOffset = 0;
    var totalResults = 0;


    /**
     * Loads all current users
     * @returns Promise
     */
    var loadUsers = function () {
        return new Promise(function (resolve) {
            API.request({
                path: '/json/users',
                method: 'get',
                cors: true,
                headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                success: function (code, data) {
                    if (code !== 200) {
                        return;
                    }
                    cache.users = data.users;
                },
                always: resolve,
            });
        });
    };

    /**
     * Loads all current deals
     * @returns Promise
     */
    var loadDeals = function () {
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
                    cache.deals = data;
                },
                always: resolve,
            });
        });
    };

    /**
     * Returns the colour of the provided product
     * @param {string} product The product
     */
    var productColour = function (product) {
        var colour = 'primary-soft';
        colour = product === 'MORTGAGE' ? 'danger-soft' : colour;
        colour = product === 'LIFE INSURANCE' ? 'success-soft' : colour;
        return colour;
    };

    /**
     * Displays an item on the customer card list
     * @param {object} customerData Contains all customer data to display
     */
     var displayCustomerCard = function (customerData) {

        var deal = cache.deals.find(function (deal) {
            return deal.id === customerData.deal;
        });

        var lender = customerData.product === 'MORTGAGE' ? customerData.mortgage_lender : '';
        lender = customerData.product === 'LIFE INSURANCE' ? customerData.life_lender_product : lender;
        lender = customerData.product === 'B AND C' ? customerData.bandc_provider : lender;

        var term = customerData.product === 'MORTGAGE' ? (customerData.mortgage_term + ' years') : '';
        term = customerData.product === 'LIFE INSURANCE' ? (customerData.life_term + ' years') : term;
        term = customerData.product === 'B AND C' ? '' : term;

        var type = customerData.product === 'MORTGAGE' ? customerData.mortgage_type : '';
        type = customerData.product === 'LIFE INSURANCE' ? customerData.life_type : type;
        type = customerData.product === 'B AND C' ? '' : type;

        return '<div class="col-12 col-md-6 col-xl-4">' +
            '<div class="card py-3 bg-' + productColour(customerData.product) + '">' +

                '<div class="row align-items-center" style="z-index: 1;">' +
                    '<div class="col-12 text-center py-3 mb-0">' +
                        '<div class="avatar">' +
                            '<span class="avatar-title rounded-circle">' +
                                '<i class="fe fe-user" style="font-size: 200%;"></i>' +
                            '</span>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                '<div class="card bg-white mx-3 mt-n5 mb-0 p-3 pt-5">' +

                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<h2 class="text-center card-title">' +
                                '<a class="item-name" href="/customers/' + customerData.slug + '">' +
                                    customerData.name +
                                    (customerData.mortgage_property !== '' ? (
                                        '<br /><span class="text-muted" style="font-size: 60%;">' + customerData.mortgage_property + '</span>'
                                    ) : '') +                
                                '</a>' +
                            '</h2>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12 text-center">' +
                            '<span class="fw-bold">' +
                                customerData.product +
                            '</span>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<h3 class="text-center my-2">' +
                                '<span class="badge" style="background-color: ' + (deal && deal.colour ? deal.colour : '#444') + ';">' +
                                    (deal && deal.name ? deal.name : 'Unknown deal') +
                                '</span>' +
                            '</h3>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12 text-center">' +
                            '<span class="text-muted">' +
                                lender +
                            '</span>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12 text-center">' +
                            '<a href="/customers/' + customerData.slug + '" class="btn btn-sm btn-primary">' +
                                'Edit' +
                            '</a>' +
                        '</div>' +
                    '</div>' +

                '</div>' +

            '</div>' +
        '</div>';
    };

    /**
     * Displays an item on the customer list
     * @param {object} customerData Contains all customer data to display
     */
    var displayCustomer = function (customerData) {
        
        var deal = cache.deals.find(function (deal) {
            return deal.id === customerData.deal;
        });

        var lender = customerData.product === 'MORTGAGE' ? customerData.mortgage_lender : '';
        lender = customerData.product === 'LIFE INSURANCE' ? customerData.life_lender_product : lender;
        lender = customerData.product === 'B AND C' ? customerData.bandc_provider: lender;

        var term = customerData.product === 'MORTGAGE' ? (customerData.mortgage_term + ' years') : '';
        term = customerData.product === 'LIFE INSURANCE' ? (customerData.life_term + ' years') : term;
        term = customerData.product === 'B AND C' ? '' : term;

        var type = customerData.product === 'MORTGAGE' ? customerData.mortgage_type : '';
        type = customerData.product === 'LIFE INSURANCE' ? customerData.life_type : type;
        type = customerData.product === 'B AND C' ? '' : type;

        return '<tr class="bg-' + productColour(customerData.product) + '">' +

            '<td>' +
                '<a class="item-name text-reset" href="/customers/' + customerData.slug + '">' +
                    customerData.name +
                    (customerData.mortgage_property !== '' ? (
                        '<br /><span class="text-muted">' + customerData.mortgage_property + '</span>'
                    ) : '') +
                    (customerData.product === 'B AND C' ? (
                        '<br /><span class="text-muted">' + customerData.bandc_property_postcode + ' - ' + customerData.bandc_policy_number + '</span>'
                    ) : '') +

                '</a>' +
            '</td>' +

            '<td class="text-center">' +
                '<span class="item-title fw-bold">' +
                    customerData.product +
                '</span>' +
            '</td>' +

            '<td class="text-center">' +
                // '<span class="item-title">' +
                    '<span class="badge" style="background-color: ' + (deal && deal.colour ? deal.colour : '#444') + ';">' +
                        (deal && deal.name ? deal.name : 'Unknown deal') +
                    '</span>' +
                // '</span>' +
            '</td>' +

            '<td class="text-center">' +
                '<span class="item-title">' + type + '</span>' +
            '</td>' +

            '<td class="text-center">' +
                '<span class="item-title">' + term + '</span>' +
            '</td>' +

            '<td class="text-center">' +
                '<span class="item-title text-muted">' + lender + '</span>' +
            '</td>' +

            '<td class="text-center">' +
                '<a href="/customers/' + customerData.slug + '" class="btn btn-sm btn-primary">' +
                    'Edit' +
                '</a>' +
            '</td>' +

        '</tr>';
    };


    var displayPaging = function (pageData) {
        var resultsPerPage = parseInt(document.querySelector('.filter-results-per-page').value);

        totalResults = pageData.total;

        var currentPage = Math.floor(pageData.currentOffset / resultsPerPage);
        var totalPages = Math.ceil(pageData.total / resultsPerPage);

        var pageHTML = '';

        for (var pageIndex = 0; pageIndex < totalPages; pageIndex++) {
            pageHTML += '<li' + (currentPage === pageIndex ? ' class="active"' : '') + '>' +
                '<a class="page" href="#" data-offset="' + (pageIndex * resultsPerPage) + '">' +
                    (pageIndex + 1) +
                '</a>' +
            '</li>';
        }

        document.querySelectorAll('.list-pagination').forEach(function (element) {
            element.innerHTML = pageHTML;
        });

        document.querySelectorAll('.page').forEach(function (element) {
            element.addEventListener('click', function(event) { 
                event.preventDefault();
                currentOffset = parseInt(element.getAttribute('data-offset'));
                loadSubmit();
            });
        });
    };

    /**
     * Displays the customer page list
     * @param {object} data Contains all customer data to display and page information
     */
    var displayList = function (data) {
        document.querySelector('table.table tbody').innerHTML = data.customers.map(displayCustomer).join('');
        document.querySelector('#contactsCards div.list').innerHTML = data.customers.map(displayCustomerCard).join('');
        displayPaging({
            currentOffset: currentOffset,
            total: data.total,
        });
    };

    /**
     * Triggers a load of customer data
     */
    var load = function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            e.stopPropagation();
        }
        if (loadBounce !== false) {
            clearTimeout(loadBounce);
        }
        loadBounce = setTimeout(loadSubmit, loadBounceDelay);
    };

    /**
     * Loads customer data from the server
     */
    var loadSubmit = function () {
        loadBounce = false;

        var queryParts = [];

        queryParts.push('offset=' + currentOffset);
        queryParts.push('limit=' + parseInt(document.querySelector('.filter-results-per-page').value));

        if (document.querySelector('.list-search').value !== '') {
            queryParts.push('search=' + encodeURIComponent(document.querySelector('.list-search').value));
        }
        if (document.querySelector('.filter-products').value !== 'ALL') {
            queryParts.push('product=' + encodeURIComponent(document.querySelector('.filter-products').value));
        }
        if (document.querySelector('.filter-deal').value !== '') {
            queryParts.push('deal=' + encodeURIComponent(document.querySelector('.filter-deal').value));
        }

        API.request({
            path: '/json/customers?' + queryParts.join('&'),
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    document.querySelector('table.table tbody').innerHTML = '<tr><td colspan="6" class="text-center py-5">' +
                        'Failed to get customers: error code ' + code + ' returned from server' +
                    '</td></tr>';
                    document.querySelector('#contactsCards div.list').innerHTML = '<div class="text-center py-5">' +
                        'Failed to get customers: error code ' + code + ' returned from server' +
                    '</div>';
                    return;
                }

                try {
                    displayList(data);
                } catch (e) {
                    console.error(e.message, e.stack);
                }
            },
            error: function () {
                document.querySelector('table.table tbody').innerHTML = '<tr><td colspan="6" class="text-center py-5">' +
                    'Failed to connect to server, please try again' +
                '</td></tr>';
                document.querySelector('#contactsCards div.list').innerHTML = '<div class="text-center py-5">' +
                    'Failed to connect to server, please try again' +
                '</div>';
            },
        });
    };

    /**
     * Browses to the next page
     */
    var nextPage = function () {
        var resultsPerPage = parseInt(document.querySelector('.filter-results-per-page').value);

        if ((currentOffset + resultsPerPage) >= totalResults) {
            return;
        }

        currentOffset += resultsPerPage;
        loadSubmit();
    };

    /**
     * Browses to the previous page
     */
    var previousPage = function () {
        if (currentOffset <= 0) {
            return;
        }

        var resultsPerPage = parseInt(document.querySelector('.filter-results-per-page').value);
        currentOffset -= resultsPerPage;
        currentOffset = currentOffset < 0 ? 0 : currentOffset;
        loadSubmit();
    };


    // Create global event handlers
    document.querySelector('.list-search').addEventListener('keyup', load);
    document.querySelector('[type=submit]').addEventListener('click', loadSubmit);
    document.querySelectorAll('.list-pagination-prev a').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            previousPage();
        });
    });
    document.querySelectorAll('.list-pagination-next a').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            nextPage();
        });
    });
    document.querySelector('.filter-results-per-page').addEventListener('change', function () {
        currentOffset = 0;
        loadSubmit();
    });
    document.querySelector('.filter-products').addEventListener('change', function () {
        currentOffset = 0;
        loadSubmit();
    });
    document.querySelector('.filter-deal').addEventListener('change', function () {
        currentOffset = 0;
        loadSubmit();
    });


    // Perform an initial load of all required objects
    loadUsers()
    .then(loadDeals)
    .then(loadSubmit);

})();