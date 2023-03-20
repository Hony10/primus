(function() {
    'use strict';

    var createUser = function () {
        loading(true);

        // Quickly check to see if password fields match and are greater than 6 characters if they are entered
        var pass1 = document.querySelector('[name=password1]').value;
        var pass2 = document.querySelector('[name=password2]').value;
        if ((pass1 !== '') || (pass2 !== '')) {
            if (pass1.length < 6) {
                errorMessage({
                    message: 'Cannot create user account:',
                    errors: ['Entered password must be 6 characters or greater.']
                });
                loading(false);
                return;
            }
            if (pass1.value !== pass2.value) {
                errorMessage({
                    message: 'Cannot create user account:',
                    errors: ['Entered passwords do not match, please re enter the passwords and try again.']
                });
                loading(false);
                return;
            }
        }


        // Build request data
        var postData = {
            username: document.querySelector('[name=email-address]').value,
            password: document.querySelector('[name=password1]').value,
            firstName: document.querySelector('[name=first-name]').value,
            lastName: document.querySelector('[name=last-name]').value,
            roles: '',
        };
        

        if (document.querySelector('[name=permissions-global-admin]').checked) {
            postData.roles += (postData.roles !== '' ? ',' : '') + 'Global Administrator';
        }
        if (document.querySelector('[name=permissions-agent]').checked) {
            postData.roles += (postData.roles !== '' ? ',' : '') + 'Agent';
        }

        // Execute the request
        API.request({
            path: '/user',
            method: 'post',
            cors: true,
            data: postData,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    errorMessage(data);
                    return;
                }

                success();
            },
            error: function () {
                errorMessage('Failed to create user account, no connection to server, please try again.');
            },
            always: function () {
                loading(false);
            }
        });
    };

    var errorMessage = function (message) {
        document.querySelector('.create-user-error span:nth-child(2)').innerHTML = message.message + (typeof(message.errors) !== 'undefined' ? (
            '<ul class="mt-2">' +
                Object.keys(message.errors).map(function (key) {
                    return '<li>' +
                        message.errors[key] +
                    '</li>';
                }).join('') +
            '</ul>'
        ) : '');
        document.querySelector('.create-user-error').style.display = '';

    };

    var success = function (userData) {
        // Go back to the users page
        location.href = '/users';
    };

    var loading = function (state) {
        // Disable form elements if loading
        if (state) {
            document.querySelector('.create-user-error').style.display = 'none';
            document.querySelectorAll('form input,form button').forEach(function (element) {
                element.setAttribute('disabled', 'disabled');
            });
            document.querySelector('.create-user-loading').style.display = '';
            return;
        }

        // Enable form elements
        document.querySelectorAll('form input,form button').forEach(function (element) {
            element.removeAttribute('disabled');
        });
        document.querySelector('.create-user-loading').style.display = 'none';
    };

    document.querySelector('.button-create-user').addEventListener('click', createUser);

})();