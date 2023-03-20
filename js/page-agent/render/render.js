(function () {
    'use strict';


    // Store references for current filter settings
    var dateFilter = { from: '', to: '' };
    var customerFilter = { product: '', agent: 0 };

    // Cache for customers and payments
    var customers = [];
    var dates = [];

    // Validate current filters, if not valid render a message on the page and return false
    var validateFilters = function () {
        if (customerFilter.product === '') {
            renderError('No valid product has been selected');
            return false;
        }
        if (customerFilter.agent < 1) {
            renderError('No valid agent has been selected');
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
        document.querySelector('#agentActivityList').innerHTML = '<div class="p-5">' +
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

        document.querySelector('#agentActivityList').innerHTML = '';

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

        Agent.load({ dates: dateFilter, customer: customerFilter })
        .then(function (activityData) {
            Agent.draw({
                activity: activityData,
                dates: dates,
            });
        })
        .catch(function (err) {
            console.log(err);
            renderError('Failed to load commissions: ' + err);
        });
    };

    // Redraw table dates only
    var dateRefresh = function () {
        fullRefresh();
        // if (!validateFilters()) { return; }
    };

    // Render the commissions table
    var render = function () {

        // Get current filter settings
        var newDateFilter = {
            from: document.querySelector('[name=filter-date-from]').value,
            to: document.querySelector('[name=filter-date-to]').value,
        };
        var dateFilterChanged = !((newDateFilter.from === dateFilter.from) && (newDateFilter.to === dateFilter.to));

        // Get current customer filter settings
        var newCustomerFilter = {
            agent: document.querySelector('[name=filter-agent]').value,
        };
        var customerFilterChanged = !((newCustomerFilter.product === customerFilter.product) && (newCustomerFilter.agent === customerFilter.agent));

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



    // Create event handlers for when the filter changes
    document.querySelector('[name=filter-agent]').addEventListener('change', render);
    document.querySelector('[name=filter-date-from]').addEventListener('change', render);
    document.querySelector('[name=filter-date-to]').addEventListener('change', render);


    // Set the first render for just after the page loading
    setTimeout(function() {
        render();
    }, 20);

    Agent.render = render;
    Agent.refresh = fullRefresh;

})();