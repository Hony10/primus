(function() {
    'use strict';

    var load = function (filterData) {
        return new Promise(function (resolve, reject) {

            Agent.data = [];

            var filter = {
                dateFrom: filterData.dates.from,
                dateTo: filterData.dates.to,
                agent: filterData.customer.agent,
            };

            API.request({
                path: '/json/costs',
                method: 'get',
                cors: true,
                headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                data: filter,
                success: function (code, data) {
                    if (code !== 200) {
                        reject();
                    }
                    Agent.data = data;
                    resolve(data);
                },
                error: function () {
                    reject();
                }
            });
        });
    };

    Agent.load = load;

})();