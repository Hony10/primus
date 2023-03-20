(function () {
    'use strict';

    // Load all users as specified by the selected filters
    var load = function () {
        return new Promise(function (resolve, reject) {

            var filter = {
                limit: -1,
            };
            if ((document.querySelector('[name=filter-product]').value !== '') && (document.querySelector('[name=filter-product]').value !== 'ALL')) {
                filter.product = document.querySelector('[name=filter-product]').value;
            }
            if ((document.querySelector('[name=filter-deal]').value !== '') && (document.querySelector('[name=filter-deal]').value !== '*')) {
                filter.deal = document.querySelector('[name=filter-deal]').value;
            }
            if (document.querySelector('[name=filter-search]').value !== '') {
                filter.search = document.querySelector('[name=filter-search]').value;
            }

            API.request({
                path: '/json/customers',
                method: 'get',
                cors: true,
                headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                data: filter,
                success: function (code, data) {
                    if (code !== 200) {
                        return;
                    }
                    resolve(data.customers);
                },
                error: function () {
                    reject();
                }
            });
        });
    };

    PaymentEntries.loadCustomers = load;

})();