(function () {
    'use strict';

    var months = [
        'JAN',
        'FEB',
        'MAR',
        'APR',
        'MAY',
        'JUN',
        'JUL',
        'AUG',
        'SEP',
        'OCT',
        'NOV',
        'DEC',
    ];

    // Draws a customer row
    var drawCustomerRow = function (customer, dates) {
        // Strip any non ascii character out of the customer string
        var jsonCustomer = JSON.stringify(customer); 
        var finalCustomerStr = '';
        for (var i = 0; i < jsonCustomer.length; i++) {
            if (jsonCustomer[i].charCodeAt(0) <= 255) {
                finalCustomerStr += jsonCustomer[i];
            }
        }
        jsonCustomer = finalCustomerStr;

        return '<tr class="customer-row" data-customer-id="' + customer.id + '">' +
            '<input type="hidden" class="customer-data" value="' + btoa(jsonCustomer) + '" />' +
            '<td>' +
                '<h4>' + customer.name + '</h4>' +
                ((customer.product === 'MORTGAGE') ? (
                    '<p class="font-weight-light mb-0">' +
                        customer.mortgage_property +
                    '</p>'
                ) : '') +
                ((customer.product === 'B AND C') ? (
                    '<p class="font-weight-light mb-0">' +
                        customer.bandc_property_postcode +
                        ' - ' +
                        customer.bandc_policy_number +
                    '</p>'
                ) : '') +
                '<div class="badge ' +
                    (customer.product === 'MORTGAGE' ? 'bg-danger-soft' : '') +
                    (customer.product === 'LIFE INSURANCE' ? 'bg-success-soft' : '') +
                    (customer.product === 'B AND C' ? 'bg-primary-soft' : '') +
                '">' +
                    customer.product +
                '</div>' +
                '<br />' +
                '<div class="badge bg-primary">' +
                    (customer.product === 'MORTGAGE' ? customer.mortgage_lender : '') +
                    (customer.product === 'LIFE INSURANCE' ? customer.life_lender_product : '') +
                    (customer.product === 'B AND C' ? customer.bandc_provider : '') +
                '</div>' +
            '</td>' +
            '<td class="fw-bold fs-4">' + customer.mk + '</td>' +
            drawDateColumns(customer, dates) +
        '</tr>';
    };

    // Draw all date columns allowing for data entry
    var drawDateColumns = function (customer, dates) {
        return dates.map(function (date) {
            return '<td class="customer-cell" data-date="' + date + '" style="min-width: 100px; vertical-align: top;">' +

                '<div class="payments-current-list">' +
                '</div>' +

                '<div class="payments-actions">' +
                    '<button class="btn btn-sm btn-rounded-circle btn-white payments-add" title="Add Payment">' +
                        '<span class="fe fe-plus"></span>' +
                    '</button>' +
                '</div>' +

            '</td>';
        }).join('');
    };

    // Draws the table using the supplied configuration
    var draw = function (drawConfig) {
        document.querySelector('#paymentsList').innerHTML = //'<div class="table-responsive">' +
            '<table class="table table-sm table-hover table-nowrap card-table">' +
                '<thead>' +
                    '<tr>' +
                        '<th>Name</th>' +
                        '<th>MK</th>' +
                        drawConfig.dates.map(function (date) {
                            var curDate = new Date(date);
                            return '<th>' +
                                months[curDate.getMonth()] +
                                '-' +
                                curDate.getFullYear().toString().substr(-2) +
                            '</th>';
                        }).join('') +
                    '</tr>' +
                '</thead>' +
                '<tbody>' +
                    drawConfig.customers.map(function (customer) {
                        return drawCustomerRow(customer, drawConfig.dates);
                    }).join('') +
                '</tbody>' +
            '</table>';
        //'</div>';

        // Now load all payments for each of these customers, load 4 customers at a time
        var currentLoading = 0;
        var currentIndex = 0;
        var batchMax = 4;
        var rows = document.querySelectorAll('#paymentsList .customer-row');
        var loadBatch = function () {
            if (currentLoading > batchMax) {
                setTimeout(loadBatch, 20);
                return;
            }

            var start = currentIndex;
            var end = currentIndex + (batchMax - currentLoading);
            if (end > rows.length) {
                end = rows.length;
            }
            
            console.log('loading ' + start + ' - ' + end);

            for (var i = start; i < end; i++) {
                currentLoading++;
                PaymentEntries.refreshCustomer(
                    parseInt(rows[i].getAttribute('data-customer-id')),
                    rows[i],
                    function () {
                        currentLoading--;
                    }
                );
            }

            currentIndex = end;

            if (currentIndex < rows.length) {
                setTimeout(loadBatch, 20);
            }
        };
        loadBatch();

        // for (var i = 0; i < document.querySelectorAll('#paymentsList .customer-row').length; i++) {

        // }

        // document.querySelectorAll('#paymentsList .customer-row').forEach(function (row) {
        //     PaymentEntries.refreshCustomer(parseInt(row.getAttribute('data-customer-id')), row);
        // });

    };


    PaymentEntries.draw = draw;

})();