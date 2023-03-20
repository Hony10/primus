(function () {
    'use strict';

    var addCostConfirm = function () {
        var requestData = {
            date: document.querySelector('[name=add-cost-date]').value,
            value: document.querySelector('[name=add-cost-value]').value,
            agent: document.querySelector('[name=filter-agent]').value,
            details: document.querySelector('[name=add-cost-details]').value,
            type: 'standard',
            // type: 'commission-payment',
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

    Agent.addCost = function () {
        document.querySelector('[name=add-cost-value]').value = '0.00';
        document.querySelector('[name=add-cost-details]').value = '';
        addCostModal.show();
    };

    var addCostModal = new bootstrap.Modal(document.getElementById('addCost'));
    document.querySelector('.modal-add-cost-confirm').addEventListener('click', addCostConfirm);
})();
