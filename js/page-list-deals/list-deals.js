(function () {
    'use strict';

    try {

        var _users = [];

        var loadUsers = function () {
            return new Promise(function (resolve) {
                API.request({
                    path: '/json/users',
                    method: 'get',
                    cors: true,
                    headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                    success: function (code, data) {
                        if (code !== 200) {
                            return;
                        }
                        _users = data.users;
                    },
                    always: resolve,
                });
            });
        };

        var loadDeals = function () {
            API.request({
                path: '/json/deals',
                method: 'get',
                cors: true,
                headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                success: function (code, data) {
                    if (code !== 200) {
                        return;
                    }

                    data = data.sort(function (a, b) {
                        return a.name.toLowerCase() > b.name.toLowerCase() ? 1 : (
                            a.name.toLowerCase() < b.name.toLowerCase() ? -1 : 0
                        );
                    });

                    document.querySelector('.deals-total').innerHTML = data ? data.length : '0';
                    displayList(data);
                }
            });
        };

        var displayRow = function (deal) {
            return '<div class="row my-3 align-items-center">' +
                '<div class="col-12 col-md-3 ms-2 my-3 align-top">' +
                    '<div ' +
                        'class="me-3 rounded-circle d-inline-block" ' +
                        'style="background-color: ' + deal.colour + '; min-height: 32px; min-width: 32px;overflow: hidden;" ' +
                    '></div>' +
                    '<h4 class="mb-1 d-inline-block align-top" style="padding-top: 8px;">' +
                        deal.name +
                    '</h4>' +
                '</div>' +

            '<div class="col-12 col-md-auto me-auto align-items-top">' +
                Object.keys(deal.assignments).map(function (key) {
                    var user = _users.find(function (user) {
                        return user.id === parseInt(key);
                    });
                    return deal.assignments[key] <= 0 ? '' :
                    '<div class="text-muted">' +
                        '<strong>' +
                        (deal.assignments[key] ? deal.assignments[key] : 0) +
                        ' &percnt;</strong>' +
                        ' &nbsp; ' + (user.first_name + ' ' + user.last_name).trim() +
                    '</div>';
                }).join('') +
            '</div>' +

                '<div class="col-auto">' +
                    '<a href="/deals/' + deal.slug + '" class="btn btn-sm btn-primary me-2">Edit</a>' +
                    '<a href="#" class="deal-delete btn btn-sm btn-danger" data-deal="' + deal.slug + '">Delete</a>' +
                '</div>' +

            '</div>';
        };

        var displayList = function (data) {
            document.querySelector('.deals-list').innerHTML = data.map(displayRow).join('');

            document.querySelectorAll('.deal-delete').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    API.request({
                        path: '/json/deals/' + element.getAttribute('data-deal'),
                        method: 'delete',
                        cors: true,
                        headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
                        always: loadDeals,
                    });
            
                });
            });
        };

        
        // Loads the deals list
        loadUsers()
        .then(loadDeals);

    } catch (e) {
        console.error(e.message, e.stack);
    }

})();