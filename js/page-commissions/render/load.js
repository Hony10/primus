(function() {
    'use strict';

    var load = function (filterData) {
        return new Promise(function (resolve, reject) {

            Commissions.data = [];

            var filter = {
                dateFrom: filterData.dates.from,
                dateTo: filterData.dates.to,
                agent: filterData.customer.agent,
            };
            if ((filterData.customer.product !== '') && (filterData.customer.product !== 'ALL')) {
                filter.product = filterData.customer.product;
            }

            API.request({
                path: '/json/commissions',
                method: 'get',
                cors: true,
                headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                data: filter,
                success: function (code, data) {
                    console.log(code, data);
                    if (code !== 200) {
                        reject();
                    }
                    Commissions.data = data;
                    resolve(data);
                },
                error: function () {
                    reject();
                }
            });
        });
    };

    Commissions.load = load;

})();