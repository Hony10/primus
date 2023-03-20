/**
 * Statements JavaScript Document.
 * 
 * @author James Plant <james.plant@whitestores.co.uk>
 * @since  1.0.0
 */

var Statements = function () {
    'use strict';

    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    
    // Draw a single month
    var drawMonth = function (date, data) {
        var bAndC = 0.00;
        var lifeInsurance = 0.00;
        var mortgageTotal = 0.00;
        var total = 0.00;

        return  '<h3>Payments for ' + months[date.getMonth()] + ' ' + date.getFullYear() + '</h3>' +
            '<div>' +
                '<div class="card">' +
                    '<table class="table table-sm table-hover table-nowrap card-table">' +
                        '<tbody>' +
                            data.map(function (payment) {
                                bAndC += payment.product === 'B AND C' ? parseFloat(payment.value) : 0;
                                lifeInsurance += payment.product === 'LIFE INSURANCE' ? parseFloat(payment.value) : 0;
                                mortgageTotal += payment.product === 'MORTGAGE' ? parseFloat(payment.value) : 0;
                                total += parseFloat(payment.value);

                                return '<tr>' +
                                    '<td>' + payment.value + '</td>' +
                                    '<td>' + payment.product + '</td>' +
                                    '<td>' + (payment.customer_name ? payment.customer_name : payment.customer) + '</td>' +
                                    '<td>' + (payment.deal_name ? payment.deal_name : 'UNKNOWN DEAL') + '</td>' +
                                '</tr>';
                            }).join('') +
                        '</tbody>' +
                    '</table>' +
                '</div>' +
                '<div class="position-sticky bottom-0 card">' +
                    '<div class="card-body">' +
                    '<div class="row">' +
                        '<div class="col-12 col-md-4">' +
                            '<div class="card bg-secondary-soft">' +
                                '<div class="card-body p-3">' +
                                    '<h5>B AND C</h5>' +
                                    '<span class="fs-2">&pound; ' + bAndC.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-12 col-md-4">' +
                            '<div class="card bg-secondary-soft">' +
                                '<div class="card-body p-3">' +
                                    '<h5>LIFE INSURANCE</h5>' +
                                    '<span class="fs-2">&pound; ' + lifeInsurance.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-12 col-md-4">' +
                            '<div class="card bg-secondary-soft">' +
                                '<div class="card-body p-3">' +
                                    '<h5>MORTGAGE</h5>' +
                                    '<span class="fs-2">&pound; ' + mortgageTotal.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<div class="card bg-primary-soft m-0">' +
                                '<div class="card-body p-3">' +
                                    '<h4>Total</h4>' +
                                    '<span class="fs-1">&pound; ' + total.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
    };

    // Draw all data
    var drawData = function (data) {
        // Get all months that we want to display
        var date = new Date(document.querySelector('[name=filter-date-from]').value);
        var dateTo = new Date(document.querySelector('[name=filter-date-to]').value);
        date.setDate(1);
        dateTo.setDate(1);

        var result = [];

        // Loop through each month that we have selected and draw each month
        while ((date.getTime() / 1000) <= (dateTo.getTime() / 1000)) {
            var nextMonth = new Date(date.getTime());
            nextMonth.setMonth(nextMonth.getMonth() + 1);

            var filteredData = data.filter(function (element) {
                var elementDate = new Date(element.date).getTime();
                return (elementDate >= date.getTime()) && (elementDate <= nextMonth.getTime());
            });

            result.push(drawMonth(date, filteredData));
            date.setMonth(date.getMonth() + 1);
        }

        return result;
    };

    // Refresh the statements view
    var refresh = function () {
        var filterData = {};

        if (document.querySelector('[name=filter-date-from]').value !== '') {
            filterData.dateFrom = document.querySelector('[name=filter-date-from]').value;
        }
        if (document.querySelector('[name=filter-date-to]').value !== '') {
            filterData.dateTo = document.querySelector('[name=filter-date-to]').value;
        }
        if (document.querySelector('[name=filter-product]').value !== 'ALL') {
            filterData.product = document.querySelector('[name=filter-product]').value;
        }
        if (document.querySelector('[name=filter-deal]').value !== '*') {
            filterData.deal = document.querySelector('[name=filter-deal]').value;
        }

        API.request({
            path: '/json/payments/statement',
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            data: filterData,
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }
                document.querySelector('.payments-list').innerHTML = drawData(data).join('<hr class="my-5" />');
            },
        });
    };


    // Execute the first page refresh
    refresh();


    // Add event handlers for executing another refresh
    document.querySelector('[name=filter-date-from]').addEventListener('change', refresh);
    document.querySelector('[name=filter-date-to]').addEventListener('change', refresh);
    document.querySelector('[name=filter-product]').addEventListener('change', refresh);
    document.querySelector('[name=filter-deal]').addEventListener('change', refresh);


    // Return public methods and properties
    return {
        refresh: refresh,
    };

}();
