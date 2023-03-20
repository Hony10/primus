(function () {
    'use strict';


    // Store references for current filter settings
    var dateFilter = { from: '', to: '' };
    var customerFilter = { product: '', deal: 0, search: '' };

    // Cache for customers and payments
    var customers = [];
    var dates = [];

    // Bounce customer text search
    var searchBounce = false;
    
    
    // Validate current filters, if not valid render a message on the page and return false
    var validateFilters = function () {
        if (customerFilter.product === '') {
            renderError('No valid product has been selected');
            return false;
        }
        if (customerFilter.deal < 1) {
            renderError('No valid deal has been selected');
            return false;
        }

        if (dateFilter.from === '') {
            renderError('Date from is not valid');
            return false;
        }
        if (dateFilter.to === '') {
            renderError('Date to is not valid');
            return false;
        }
        return true;
    };

    // Displays an error message on the table display
    var renderError = function (errorMessage) {
        document.querySelector('#paymentsList').innerHTML = '<div class="p-5">' +
            '<div class="col-12 col-md-6 mx-auto">' +
                '<div class="alert alert-danger my-5">' +
                    '<i class="fe fe-alert-triangle me-3"></i>' +
                    errorMessage +
                '</div>' +
            '</div>' +
        '</div>';
    };

    // Redraw the full table including customers and dates
    var fullRefresh = function () {
        if (!validateFilters()) { return; }

        document.querySelector('#paymentsList').innerHTML = '';

        // Create dates
        dates = [];
        var startDate = new Date(dateFilter.from);
        var endDate = new Date(dateFilter.to);
        startDate.setDate(1);
        endDate.setDate(1);

        var curDate = startDate;

        while (curDate.getTime() < endDate.getTime()) {
            dates.push(curDate.getFullYear() + '-' + ('00' + (curDate.getMonth() + 1)).substr(-2) + '-01');
            curDate.setMonth(curDate.getMonth() + 1);
            if (dates.length > 50) {
                break;
            }
        }

        PaymentEntries.loadCustomers()
        .then(function (customerData) {
            customers = customerData;

            PaymentEntries.draw({
                customers: customers,
                dates: dates,
            });

            PaymentEntries.createHandlers();
        })
        .catch(function (err) {
            console.log(err);
            renderError('Failed to load customers: ' + err);
        });
    };

    // Redraw table dates only
    var dateRefresh = function () {
        fullRefresh();
        // if (!validateFilters()) { return; }
    };

    // Draw the payment entry table
    var render = function () {

        // Get current filter settings
        var newDateFilter = {
            from: document.querySelector('[name=filter-date-from]').value,
            to: document.querySelector('[name=filter-date-to]').value,
        };
        var dateFilterChanged = !((newDateFilter.from === dateFilter.from) && (newDateFilter.to === dateFilter.to));

        // Get current customer filter settings
        var newCustomerFilter = {
            product: document.querySelector('[name=filter-product]').value,
            deal: document.querySelector('[name=filter-deal]').value,
            search: document.querySelector('[name=filter-search]').value,
        };
        var customerFilterChanged = !((newCustomerFilter.product === customerFilter.product) && (newCustomerFilter.deal === customerFilter.deal) && (newCustomerFilter.search === customerFilter.search));

        if (customerFilterChanged) {
            dateFilter = newDateFilter;
            customerFilter = newCustomerFilter;
            return fullRefresh();
        } else if (dateFilterChanged) {
            dateFilter = newDateFilter;
            return dateRefresh();
        }

        // No need to redraw or refresh the table
        return;
    };

    var searchHandler = function () {
        if (searchBounce !== false) {
            clearTimeout(searchBounce);
        }
        searchBounce = setTimeout(function () {
            render();
            searchBounce = false;
        }, 250);
    };


    // Create event handlers for when the filter changes
    document.querySelector('[name=filter-product]').addEventListener('change', render);
    document.querySelector('[name=filter-deal]').addEventListener('change', render);
    document.querySelector('[name=filter-date-from]').addEventListener('change', render);
    document.querySelector('[name=filter-date-to]').addEventListener('change', render);
    document.querySelector('[name=filter-search]').addEventListener('keyup', searchHandler);



    // Set the first render for just after the page loading
    setTimeout(function() {
        render();
    }, 20);

    PaymentEntries.render = render;

})();