(function () {
    'use strict';

    // Attach to page elements
    var form = {
        token: document.querySelector('[name=_token]'),
        username: document.querySelector('[name=username]'),
        password: document.querySelector('[name=password]'),
        passwordToggle: document.querySelector('.password-toggle'),
        submit: document.querySelector('[name=sign-in-submit]'),
        alert: document.querySelector('.alert'),
        alertText: document.querySelector('.alert .alert-text'),
    };


    /**
     * Attempts to submit the form and sign in
     */
    var signIn = function () {
        // Always change the password input back to a password
        form.password.type = 'password';

        // Disable all form elements while we attempt to sign in
        form.alert.classList.remove('show');
        form.username.setAttribute('disabled', 'disabled');
        form.password.setAttribute('disabled', 'disabled');
        form.submit.setAttribute('disabled', 'disabled');
        form.submit.innerHTML = '<div class="spinner-grow spinner-grow-sm me-3" role="status">' +
            '<span class="sr-only"></span>' +
        '</div>' + 'Signing you in...';

        var postData = {
            username: form.username.value,
            password: form.password.value,
        };

        // Send request to sign in 
        API.request({
            path: '/sign-in',
            method: 'post',
            cors: true,
            data: postData,
            headers: {'X-CSRF-TOKEN': form.token.value},
            success: function (code, data) {
                if (code !== 200) {
                    // If we are already logged in, just go to the home page
                    if (data.message === 'Already logged in') {
                        location.href = '/';
                    }
                    
                    form.alert.classList.add('show');
                    form.alertText.textContent = 'Failed to sign you in: ' + data.message;
                    return;
                }

                // Sign in successful, redirect to home page
                location.href = '/';
            },
            error: function () {
                form.alert.classList.add('show');
                form.alertText.textContent = 'Failed to sign you in, an unknown error occurred, please try again.';
            },
            always: function () {
                form.username.removeAttribute('disabled');
                form.password.removeAttribute('disabled');
                form.submit.removeAttribute('disabled');
                form.submit.textContent = 'Sign in';
            },
        });
    };

    /**
     * Toggle the password between password and plain text
     */
    var togglePassword = function () {
        form.password.type = form.password.getAttribute('type') === 'password' ? 'text' : 'password';
    };

    /**
     * Checks if an enter key press has been detected and submits the form
     * @param {object} event An event listener event
     */
    var submitOnEnter = function (event) {
        if (event.key === 'Enter') {
            signIn();
        }
    };


    // Create all required event handlers
    form.passwordToggle.addEventListener('click', togglePassword);
    form.submit.addEventListener('click', signIn);
    form.username.addEventListener('keyup', submitOnEnter);
    form.password.addEventListener('keyup', submitOnEnter);

    // Setup defaults
    form.alert.style.display = '';

})();