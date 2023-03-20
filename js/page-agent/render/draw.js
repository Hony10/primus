(function () {
    'use strict';

    var draw = function (activityData) {
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        var prevDate = '';
        var runningTotal = activityData.activity.openingBalance;

        var displayResult = [];
        activityData.activity.commissions.forEach(function (commission) {
           displayResult.push(commission);
        });
        activityData.activity.costs.forEach(function (cost) {
            displayResult.push(cost);
        });

        displayResult.sort(function (a, b) {
            return a.date < b.date ? -1 : (a.date > b.date ? 1 : 0);
        });
        
        document.querySelector('#agentActivityList').innerHTML = '<table class="table table-sm table-hover table-nowrap card-table">' +
            '<tbody>' +
                '<tr>' +
                    '<td colspan="3">' +
                        '<h3>Opening Balance</h3>' +
                    '</td>' +
                    '<td colspan="2" class="text-end">' +
                        '<h3>' + activityData.activity.openingBalance.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</h3>' +
                    '</td>' +
                '</tr>' +
                displayResult.map(function (commission, commissionIndex) {
                    var result = '';

                    if (prevDate !== commission.date) {
                        var prevDateObj = new Date(prevDate);
                        var commDateObj = new Date(commission.date);

                        if ((prevDateObj.getMonth() !== commDateObj.getMonth()) || (prevDateObj.getFullYear() !== commDateObj.getFullYear())) {
                            if (commissionIndex > 0) {
                                result += '<tr>' +
                                    '<td colspan="3">' +
                                        '<h4>Monthly Balance</h4>' +
                                    '</td>' +
                                    '<td colspan="2" class="text-end">' +
                                        '<h4>' + runningTotal.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</h4>' +
                                    '</td>' +
                                '</tr>';
                            }

                            // runningTotal = 0.00;
                            prevDate = commission.date;

                            var dateObj = new Date(prevDate);

                            result += '<tr><td colspan="5"><h3>' +
                                dateObj.getDate().toString().padStart(2, '0') + 
                                '-' +
                                months[dateObj.getMonth()].toUpperCase() +
                                '-' +
                                dateObj.getFullYear().toString().substr(-2) +
                            '</h3></td></tr>';
                        } 
                    }

                    if (typeof(commission.costType) === 'string') {
                        runningTotal -= parseFloat(commission.value);

                        result += '<tr>' +
                            '<td>' +
                                (commission.details !== '' ? (
                                    '<h5 class="m-0 p-0">' +
                                        commission.details +
                                    '</h5>'
                                ) : '') +
                                '<p class="m-0 p-0">' +
                                    commission.date.split('-').reverse().join('/') +
                                '</p>' +
                                (activityData.activity.deletePermission ? (
                                    '<button class="btn btn-sm btn-danger cost-delete" data-cost-id="' + commission.id + '">' +
                                        'Delete' +
                                    '</button>'
                                ) : '') +
                            '</td>' +
                            '<td>' +
                                (commission.costType === 'standard' ? 'COST' : '') +
                                (commission.costType === 'commission-payment' ? 'COMMISSION PAYMENT' : '') +
                            '</td>' +
                            '<td>' +
                            '</td>' +
                            '<td class="fw-bold text-end text-danger">' + parseFloat(commission.value).toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</td>' +
                            '<td class="fw-bold text-end">' + parseFloat(runningTotal).toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</td>' +
                        '</tr>';

                    } else {
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
                            '<td class="fw-bold text-end">' + parseFloat(runningTotal).toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</td>' +
                        '</tr>';
                    }

                    if (commissionIndex >= (displayResult.length - 1)) {
                        result += '<tr>' +
                            '<td colspan="3">' +
                                '<h4>Monthly Balance</h4>' +
                            '</td>' +
                            '<td colspan="2" class="text-end">' +
                                '<h4>' + runningTotal.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</h4>' +
                            '</td>' +
                        '</tr>';
    
                        // result += '<tr><td colspan="4" class="text-end"><h4>' +
                        //     runningTotal.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) +
                        // '</h4></td></tr>';
                    }

                    return result;
                }).join('') +
            '</tbody>' +
        '</table>' +
        '<div class="position-sticky bottom-0 bg-white">' +
            '<div class="m-3">' +
                '<div class="row">' +
                    '<div class="col-12 col-md-4">' +
                        '<div class="card bg-secondary-soft">' +
                            '<div class="card-body p-3">' +
                                '<h5>OPENING BALANCE</h5>' +
                                '<span class="fs-2">&pound; ' + activityData.activity.openingBalance.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-4">' +
                        '<div class="card bg-secondary-soft">' +
                            '<div class="card-body p-3">' +
                                '<h5>COMMISSIONS</h5>' +
                                '<span class="fs-2">&pound; ' + activityData.activity.agentTotals.commissions.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-12 col-md-4">' +
                        '<div class="card bg-secondary-soft">' +
                            '<div class="card-body p-3">' +
                                '<h5>COSTS &amp; COMMISSIONS PAYMENTS</h5>' +
                                '<span class="fs-2">&pound; ' + activityData.activity.agentTotals.costs.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row">' +
                    '<div class="col-12">' +
                        '<div class="card bg-primary-soft m-0">' +
                            '<div class="card-body p-3">' +
                                '<h4>BALANCE</h4>' +
                                '<span class="fs-1">&pound; ' + activityData.activity.agentTotals.balance.toLocaleString('en-GB', {maximumFractionDigits: 2, minimumFractionDigits: 2}) + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                (activityData.activity.deletePermission ? (
                    '<div class="mt-3 text-end">' +
                        '<button class="btn btn-primary agent-cost-add">Add Cost</button> ' +
                        '<button class="btn btn-primary agent-commission-add">Add Commission Payment</button>' +
                    '</div>'
                ) : '') +
            '</div>' +
        '</div>';


        // Add row event handlers for deleting costs
        document.querySelectorAll('.cost-delete').forEach(function (element) {
            element.addEventListener('click', function () {
                API.request({
                    path: '/json/costs/' + element.getAttribute('data-cost-id'),
                    method: 'delete',
                    cors: true,
                    headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                    always: Agent.refresh,
                });
            });
        });


        // Add modal event handlers
        if (activityData.activity.deletePermission) {
            document.querySelector('.agent-cost-add').addEventListener('click', Agent.addCost);
            document.querySelector('.agent-commission-add').addEventListener('click', Agent.addCommissionDrawDown);
        }
    };

    Agent.draw = draw;

})();