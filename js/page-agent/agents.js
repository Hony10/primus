(function () {
    'use strict';
    

    var users = [];


    /**
     * Loads all current deals
     * @returns Promise
     */
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
                users = data.users;
            },
        });
    };


    // Create event handler for selecting a deal
    document.querySelectorAll('.agent-selected-preview .dropdown-item').forEach(function (element) {
        element.addEventListener('click', function () {
            document.querySelector('[name=filter-agent]').value = parseInt(element.getAttribute('data-agent-id'));
            Agent.render();

            var selectedAgent = users.find(function (user) { return user.id === parseInt(element.getAttribute('data-agent-id')); });

            // Create the deal preview
            document.querySelector('.agent-selected-preview > span').innerHTML = selectedAgent === 'undefined' ? (
                'No Agent Selected'
            ) : (
                '<h2 class="m-0">' +
                    (selectedAgent.first_name + ' ' + selectedAgent.last_name).trim() +
                '</h2>'
            );
        });
    });


    load();

})();