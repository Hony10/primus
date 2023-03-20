(function () {
    'use strict';

    var draw = function (commissionData) {
        console.log(commissionData);

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        var prevDate = '';
        var runningTotal = 0.00;

        document.querySelector('#commissionsList').innerHTML = '<table class="table table-sm table-hover table-nowrap card-table">' +
            '<tbody>' +
                commissionData.commissions.map(function (commission, commissionIndex) {
                    var result = '';

                    if (prevDate !== commission.date) {
                        if (commissionIndex > 0) {
                            result += '<tr><td colspan="4" class="text-end"><h4>' +
                                runningTotal.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) +
                            '</h4></td></tr>';
                        }

                        runningTotal = 0.00;
                        prevDate = commission.date;

                        var dateObj = new Date(prevDate);

                        result += '<tr><td colspan="4"><h3>' +
                            months[dateObj.getMonth()].toUpperCase() +
                            '-' +
                            dateObj.getFullYear().toString().substr(-2) +
                        '</h3></td></tr>';
                    }

                    runningTotal += parseFloat(commission.commission_value.toFixed(2));

                    result += '<tr>' +
                        '<td>' +
                            '<h5 class="m-0 p-0">' +
                                commission.customer_name +
                                ((commission.product === 'MORTGAGE') && (commission.mortgage_property !== '') ? (' - ' + commission.mortgage_property) : '') +
                            '</h5>' +
                            '<br />' +
                            commission.mk +
                        '</td>' +
                        '<td>' + commission.product + '</td>' +
                        '<td>' +
                            '<span class="badge" style="background-color: ' + commission.colour + ';">' +
                                commission.deal_name +
                            '</span>' +
                        '</td>' +
                        '<td class="fw-bold text-end">' + parseFloat(commission.commission_value).toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</td>' +
                    '</tr>';

                    if (commissionIndex >= (commissionData.commissions.length - 1)) {
                        console.log(commissionIndex);
                        result += '<tr><td colspan="4" class="text-end"><h4>' +
                            runningTotal.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) +
                        '</h4></td></tr>';
                    }

                    return result;
                }).join('') +
            '</tbody>' +
        '</table>';

    };

    Commissions.draw = draw;

})();