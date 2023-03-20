(function () {
    'use strict';

    // Creates all event handlers for dynamic content
    var createHandlers = function () {

        document.querySelectorAll('.customer-row').forEach(function (row) {
            var customerId = parseInt(row.getAttribute('data-customer-id'));

            row.querySelectorAll('.customer-cell').forEach(function (cell) {
                var date = cell.getAttribute('data-date');

                // User has clicked on new payment button, show a new input field and allow entry of
                // a brand new payment entry
                cell.querySelector('.payments-add').addEventListener('click', function () {
                    var newInput = document.createElement('input');
                    newInput.setAttribute('type', 'number');
                    newInput.setAttribute('step', '0.01');
                    newInput.className = 'form-control form-control-sm payments-add-input';
                    newInput.style.maxWidth = '100px';

                    cell.querySelector('.payments-add').classList.add('d-none');
                    cell.appendChild(newInput);

                    newInput.select();

                    var removeField = function () {
                        setTimeout(function () {
                            if (!newInput) {
                                return;
                            }
                            cell.querySelector('.payments-add').classList.remove('d-none');
                            cell.removeChild(newInput);
                            newInput = false;
                            return;
                        }, 20);
                    };

                    newInput.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') {
                            // Submit a new payment
                            PaymentEntries.addPayment(
                                row,
                                JSON.parse(atob(row.querySelector('.customer-data').value)),
                                date,
                                newInput.value
                            );

                            return removeField();
                        }

                        if ((event.key === 'Esc') || (event.key === 'Escape')) {
                            return removeField();
                        }
                    });

                    newInput.addEventListener('blur', removeField);

                });
            });

        });

    };

    // Creates all payment event handlers for the specified row
    var createPaymentHandlers = function (row) {
        var customerId = parseInt(row.getAttribute('data-customer-id'));

        row.querySelectorAll('.customer-cell').forEach(function (cell) {
            var date = cell.getAttribute('data-date');

            // User has clicked on an existing customer payment, we need to show an input field
            // to allow the value to be changed or deleted
            cell.querySelectorAll('.customer-payment').forEach(function (payment) {
                payment.addEventListener('click', function () {
                    var newInput = document.createElement('input');
                    newInput.setAttribute('type', 'number');
                    newInput.setAttribute('step', '0.01');
                    newInput.className = 'form-control form-control-sm payments-add-input';
                    newInput.style.maxWidth = '100px';
                    newInput.value = payment.getAttribute('data-original-value');

                    payment.classList.add('d-none');
                    payment.after(newInput);

                    newInput.select();

                    var removeField = function () {
                        setTimeout(function () {
                            if (!newInput) {
                                return;
                            }
                            payment.classList.remove('d-none');
                            cell.querySelector('.payments-current-list').removeChild(newInput);
                            newInput = false;
                            return;
                        }, 20);
                    };


                    newInput.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') {
                            // If empty remove the payment
                            if ((parseFloat(newInput.value) === 0.00) || (newInput.value === '')) {
                                PaymentEntries.removePayment(
                                    payment.getAttribute('data-payment-id'),
                                    JSON.parse(atob(row.querySelector('.customer-data').value)),
                                    row
                                );
                                return removeField();
                            }

                            // Submit the updated payment amount
                            PaymentEntries.updatePayment(
                                payment.getAttribute('data-payment-id'),
                                JSON.parse(atob(row.querySelector('.customer-data').value)),
                                row,
                                newInput.value
                            );
                            return removeField();
                        }

                        if ((event.key === 'Esc') || (event.key === 'Escape')) {
                            return removeField();
                        }
                    });

                    newInput.addEventListener('blur', removeField);
                });
            });
        });
    };

    PaymentEntries.createHandlers = createHandlers;
    PaymentEntries.createPaymentHandlers = createPaymentHandlers;

})();