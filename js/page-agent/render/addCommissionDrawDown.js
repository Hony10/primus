(function () {
    'use strict';

    var addCommissionConfirm = function () {
        var requestData = {
            date: document.querySelector('[name=add-commission-date]').value,
            value: document.querySelector('[name=add-commission-value]').value,
            agent: document.querySelector('[name=filter-agent]').value,
            details: document.querySelector('[name=add-commission-details]').value,
            // type: 'standard',
            type: 'commission-payment',
        };

        API.request({
            path: '/json/costs',
            method: 'post',
            cors: true,
            headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
            data: requestData,
            always: Agent.refresh,
        });
        addCostModal.hide();
    };

    Agent.addCommissionDrawDown = function () {
        document.querySelector('[name=add-commission-value]').value = '0.00';
        document.querySelector('[name=add-commission-details]').value = '';
        addCostModal.show();
    };

    var addCostModal = new bootstrap.Modal(document.getElementById('addCommissionDrawdown'));
    document.querySelector('.modal-add-commission-confirm').addEventListener('click', addCommissionConfirm);
})();
