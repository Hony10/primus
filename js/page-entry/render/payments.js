(function () {
    'use strict';

    // Displays payment in the cell specified
    var displayPayment = function (cell, payment, lockedMonths) {
        try {
            var locked = lockedMonths.some(function (month) {
                return month.month === payment.date;
            });
            var newPayment = document.createElement('div');
            newPayment.innerHTML = parseFloat(payment.value).toFixed(2);
            newPayment.className = 'btn btn-sm btn-' + (locked ? 'danger' : 'primary') + ' customer-payment mb-1 d-block';
            newPayment.style.fontSize = '100%';
            newPayment.setAttribute('data-payment-id', payment.id);
            newPayment.setAttribute('data-original-value', parseFloat(payment.value).toFixed(2));
            newPayment.setAttribute('title', (locked ? 'Locked payment' : 'Click to edit'));

            cell.querySelector('.payments-current-list').appendChild(newPayment);
        } catch (e) {
            console.log(e);
        }
    };

    // Refreshes all payments for the customer in the date range specified
    var refreshCustomer = function (customer, row, cb) {
        if (!document.body.contains(row)) {
            if (typeof(cb) === 'function') {
                cb();
            }
            return;
        }

        var startDate = new Date(document.querySelector('[name=filter-date-from]').value);
        var endDate = new Date(document.querySelector('[name=filter-date-to]').value);

        startDate.setDate(1);
        endDate.setDate(1);

        API.request({
            path: '/json/payments',
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            data: {
                customer: customer,
                dateFrom: (startDate.getFullYear() + '-' + ((startDate.getMonth() + 1).toString()).substr(-2) + '-' + (startDate.getDate().toString()).substr(-2)),
                dateTo: (endDate.getFullYear() + '-' + ((endDate.getMonth() + 1).toString()).substr(-2) + '-' + (endDate.getDate().toString()).substr(-2)),
            },
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }

                // Clear all existing payments
                row.querySelectorAll('.customer-cell .payments-current-list').forEach(function (dateCell) {
                    dateCell.innerHTML = '';
                });

                // Disable payment entries for locked months
                data.lockedMonths.forEach(function (month) {
                    row.querySelectorAll('.customer-cell').forEach(function (dateCell) {
                        if (dateCell.getAttribute('data-date') === month.month) {
                            dateCell.querySelector('.payments-add').classList.add('d-none');
                        }
                    });
                });

                // Add payments
                data.payments.forEach(function (payment) {
                    var cell = false;
                    row.querySelectorAll('.customer-cell').forEach(function (dateCell) {
                        if (dateCell.getAttribute('data-date') === payment.date) {
                            cell = dateCell;
                        }
                    });

                    if (cell === false) {
                        console.warning('Cannot render payment, no date cell found: ' + payment);
                        return;
                    }

                    displayPayment(cell, payment, data.lockedMonths);
                });

                PaymentEntries.createPaymentHandlers(row);

                if (typeof(cb) === 'function') {
                    cb();
                }
            },
        });

    };

    // Adds a new customer payment
    var addPayment = function (row, customer, date, value) {
        API.request({
            path: '/json/payments',
            method: 'post',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            data: {
                customer: customer.id,
                value: value,
                deal: customer.deal,
                product: customer.product,
                date: date,
            },
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }
            },
            always: function () {
                // We need to refresh the row for this customer if payments have changed
                refreshCustomer(customer.id, row);
            }
        });
    };

    // Updates an existing customer payment
    var updatePayment = function (paymentId, customer, row, value) {
        API.request({
            path: '/json/payments/' + paymentId,
            method: 'put',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            data: {
                value: value,
            },
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }
            },
            always: function () {
                // We need to refresh the row for this customer if payments have changed
                refreshCustomer(customer.id, row);
            }
        });
    };

    // Removes an existing payment
    var removePayment = function (paymentId, customer, row) {
        API.request({
            path: '/json/payments/' + paymentId,
            method: 'delete',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }
            },
            always: function () {
                // We need to refresh the row for this customer if payments have changed
                refreshCustomer(customer.id, row);
            }
        });
    };

    PaymentEntries.refreshCustomer = refreshCustomer;
    PaymentEntries.addPayment = addPayment;
    PaymentEntries.updatePayment = updatePayment;
    PaymentEntries.removePayment = removePayment;

})();