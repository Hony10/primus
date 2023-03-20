var pageRefresh = function () {
    'use strict';

    var refresh = function () {

        API.request({
            path: '/json/end-of-month',
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            data: {
                month: document.querySelector('[name=filter-month]').value,
            },
            success: function (code, data) {
                if (code !== 200) {
                    document.querySelector('#endOfMonthDisplay').innerHTML = '<div>' +
                        '</div>';
                    return;
                }

                try {
                    document.querySelector('#endOfMonthDisplay').innerHTML = '<div>' +
                        '<table>' +

                            '<tr>' +
                                '<td style="min-width: 200px;"></td>' +
                                '<td style="min-width: 200px;">Month Total</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="min-width: 200px; background-color: ' + deal.colour + ';" class="text-white">&pound; Received</td>';
                                }).join('') +
                            '</tr>' +

                            '<tr>' +
                                '<td></td>' +
                                '<td></td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        deal.name +
                                    '</td>';
                                }).join('') +
                            '</tr>' +

                            '<tr>' +
                                '<td>Life Insurance</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.lifeTotal) : parseFloat(prevValue)) + parseFloat(currentDeal.lifeTotal);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.lifeTotal).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>B &amp; C</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.bcTotal) : parseFloat(prevValue)) + parseFloat(currentDeal.bcTotal);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.bcTotal).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>Mortgage</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.mortgageTotal) : parseFloat(prevValue)) + parseFloat(currentDeal.mortgageTotal);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.mortgageTotal).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>&nbsp;</td>' +
                                '<td></td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white"></td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>JLM Total</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.jlmTotal) : parseFloat(prevValue)) + parseFloat(currentDeal.jlmTotal);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.jlmTotal).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>JLM Commission</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.jlmDeductions) : parseFloat(prevValue)) + parseFloat(currentDeal.jlmDeductions);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.jlmDeductions).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>&nbsp;</td>' +
                                '<td></td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white"></td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>Total After Deductions</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? (parseFloat(prevValue.jlmTotal) - parseFloat(prevValue.jlmDeductions)) : parseFloat(prevValue)) + parseFloat(currentDeal.jlmTotal) - parseFloat(currentDeal.jlmDeductions);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        (parseFloat(deal.jlmTotal) - parseFloat(deal.jlmDeductions)).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>&nbsp;</td>' +
                                '<td></td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white"></td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>Ivan Handling Fees</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.ivanDeductions) : parseFloat(prevValue)) + parseFloat(currentDeal.ivanDeductions);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.ivanDeductions).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>Ivan Commission</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.ivanCommissions) : parseFloat(prevValue)) + parseFloat(currentDeal.ivanCommissions);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.ivanCommissions).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>&nbsp;</td>' +
                                '<td></td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white"></td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td>Ivan Receives</td>' +
                                '<td>' +
                                    data.deals.reduce(function (prevValue, currentDeal) {
                                        return (isNaN(parseFloat(prevValue)) ? parseFloat(prevValue.ivanReceives) : parseFloat(prevValue)) + parseFloat(currentDeal.ivanReceives);
                                    }).toFixed(2) +
                                '</td>' +
                                data.deals.map(function (deal) {
                                    return '<td style="background-color: ' + deal.colour + ';" class="text-white">' +
                                        parseFloat(deal.ivanReceives).toFixed(2) +
                                    '</td>';
                                }).join('') + 
                            '</tr>' +

                            '<tr>' +
                                '<td></td>' +
                                '<td></td>' +
                                data.deals.map(function (deal) {
                                    return '<td class="text-center py-3">' +
                                        '<button class="btn btn-primary btn-sm download-csv" data-deal="' + deal.id + '">' +
                                            '<i class="fe fe-download me-2"></i>' +
                                            'CSV' +
                                        '</button>' +
                                    '</td>';
                                }).join('') +
                            '</tr>' +

                        '</table>' +
                    '</div>';

                    // Create event handlers for downloading CSV files
                    document.querySelectorAll('.download-csv').forEach(function (button) {
                        var dealId = parseInt(button.getAttribute('data-deal'));
                        var month = encodeURIComponent(document.querySelector('[name=filter-month]').value);
            
                        button.addEventListener('click', function () {
                            window.open('/json/end-of-month/download?month=' + month + '&deal=' + dealId);
                        });
                    });

                } catch (e) {
                    console.log(e);
                }
            },
        });

    };

    refresh();

    return refresh;
}();