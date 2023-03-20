(function () {
    'use strict';

    var load = function () {
        API.request({
            path: '/json/users',
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }

                displayList(data.users);
                document.querySelector('.users-total').textContent = data.total;
                document.querySelector('.users-total-agents').textContent = data.totalAgents;
            },
        });
    };

    var displayRow = function (user) {
        return '<div class="row align-items-center">' +
            '<div class="col-auto ms-1 me-1 my-3">' +
                '<i class="fe fe-user" style="font-size: 200%;"></i>' +
            '</div>' +
            '<div class="col-5 ms-n2 my-3">' +

                '<h4 class="mb-1">' + (user.first_name + ' ' + user.last_name).trim() + '</h4>' +
                '<p class="small text-muted mb-0">' +
                    '<a class="d-block text-reset text-truncate">' +
                        user.username +
                    '</a>' +
                '</p>' +

            '</div>' +
            '<div class="col-auto ms-auto me-n3">' +
                user.roles.split(',').sort().map(function (role) {
                    return '<span class="badge bg-primary-soft m-1">' + role +'</span>';
                }).join('') +
            '</div>' +
            '<div class="col-auto">' +
                '<a href="/users/' + user.username + '" class="btn btn-sm btn-primary">Edit</a>' +
            '</div>' +
        '</div>';
    };

    var displayList = function (data) {
        document.querySelector('.users-list').innerHTML = data.map(displayRow).join('');
    };


    // Load users list
    load();

})();