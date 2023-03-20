(function () {
    'use strict';

    // Check for a sign out button and register an event to sign out and redirect
    // to the sign in page when pressed.


    /**
     * Logs out and redirects to sign in page
     */
    var signOut = function () {
        API.request({
            path: '/sign-in',
            method: 'delete',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            always: function () {
                // Always redirect to the sign in page
                location.href = '/sign-in';
            },
        });
    };


    // Create page event handlers
    document.querySelectorAll('.sign-out').forEach(function (element) {
        element.addEventListener('click', signOut);
    });

})();