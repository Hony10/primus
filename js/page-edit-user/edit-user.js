(function() {
    'use strict';

    var form = {};


    /**
     * Loads all user details
     */
    var load = function () {
        API.request({
            path: '/json/users/' + form.user,
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    // If we failed to load user, display an error
                    form.error.classList.add('show');
                    form.errorText.textContent = 'Failed to load user details: ' + data.message;
                    return;
                }

                // Fill in user data
                form.firstName.value = data.first_name;
                form.lastName.value = data.last_name;
                form.username.value = data.username;
                form.enabled.checked = data.enabled > 0 ? true : false;
                form.permissionAdmin.checked = data.roles.indexOf('Global Administrator') >= 0 ? true : false;
                form.permissionAgent.checked = data.roles.indexOf('Agent') >= 0 ? true : false;
            },
            error: function () {
                form.error.classList.add('show');
                form.errorText.textContent = 'Failed to load user, unable to connect to server, please refresh the page';
            },
        });
    };

    /**
     * Saves the user
     */
    var save = function () {
        form.error.classList.remove('show');

        var postData = {
            firstName: form.firstName.value,
            lastName: form.lastName.value,
            username: form.username.value,
            enabled: form.enabled.checked ? 1 : 0,
            roles: '',
        };

        // Quickly check to see if password fields match and are greater than 6 characters if they are entered
        if ((form.password1.value !== '') || (form.password2.value !== '')) {
            if (form.password1.length < 6) {
                form.error.classList.add('show');
                form.errorText.textContent = 'Entered password must be 6 characters or greater.';
                return;
            }
            if (form.password1.value !== form.password2.value) {
                form.error.classList.add('show');
                form.errorText.textContent = 'Entered passwords do not match, please re enter the passwords and try again.';
                return;
            }
            postData.password = form.password1.value;
        }

        // Disable the form from further input for no
        document.querySelectorAll('form input').forEach(function (element) {
            element.setAttribute('disabled', 'disabled');
        });
        form.loading.classList.add('show');
        form.cancel.setAttribute('disabled', 'disabled');
        form.submit.setAttribute('disabled', 'disabled');
        form.submit.innerHTML = '<div class="spinner-grow spinner-grow-sm me-3" role="status">' +
            '<span class="sr-only"></span>' +
        '</div>' + 'Saving user...';


        if (form.permissionAdmin.checked) {
            postData.roles += (postData.roles !== '' ? ',' : '') + 'Global Administrator';
        }
        if (form.permissionAgent.checked) {
            postData.roles += (postData.roles !== '' ? ',' : '') + 'Agent';
        }

        API.request({
            path: '/json/users/' + form.user,
            method: 'put',
            cors: true,
            data: postData,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    // If we failed to load user, display an error
                    form.error.classList.add('show');
                    form.errorText.textContent = 'Failed to save user details: ' + data.message;
                    return;
                }

                // Redirect to users page
                location.href = '/users/';
            },
            error: function () {
                form.error.classList.add('show');
                form.errorText.textContent = 'Failed to save user, unable to connect to server, please refresh the page';
            },
            always: function () {
                document.querySelectorAll('form input').forEach(function (element) {
                    element.removeAttribute('disabled');
                });
                form.loading.classList.remove('show');
                form.cancel.removeAttribute('disabled');
                form.submit.removeAttribute('disabled');
                form.submit.innerHTML = 'Save Changes';
            },
        });

    };

    /**
     * Creates all event handlers
     */
    var createHandlers = function () {
        form.submit.addEventListener('click', save);
    };

    /**
     * Binds to all form elements
     */
    var bindForm = function () {
        form.user = document.querySelector('[name=loaded-username]').value;

        form.firstName = document.querySelector('[name=first-name]');
        form.lastName = document.querySelector('[name=last-name]');
        form.username = document.querySelector('[name=email-address]');
        form.password1 = document.querySelector('[name=password1]');
        form.password2 = document.querySelector('[name=password2]');
        form.enabled = document.querySelector('[name=account-enabled]');
        form.permissionAdmin = document.querySelector('[name=permissions-global-admin]');
        form.permissionAgent = document.querySelector('[name=permissions-agent]');
        form.submit = document.querySelector('.button-save-user');
        form.cancel = document.querySelector('.button-cancel');
        form.loading = document.querySelector('.create-user-loading');
        form.error = document.querySelector('.create-user-error');
        form.errorText = document.querySelector('.create-user-error span:nth-child(2)');

        form.error.style.display = '';
        form.loading.style.display = '';

    };


    bindForm();
    load();
    createHandlers();

})();