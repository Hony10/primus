(function() {
    'use strict';

    var loadingElement = false;
    var errorElement = false;

    /**
     * Sets the form loading state
     * @param {bool} loadingState The loading state to set
     */
    var loading = function (loadingState) {
        // Create a new loading element in the notifications area
        if ((loadingElement) && (loadingState)) {
            return;
        }
        if ((!loadingElement) && (!loadingState)) {
            return;
        }

        if (loadingState) {
            loadingElement = document.createElement('div');
            loadingElement.className = 'alert alert-secondary my-3 fade';
            loadingElement.innerHTML = '<div class="spinner-grow spinner-grow-sm me-3" role="status"></div>' +
                '<span>Creating Deal...</span>';
            
            document.querySelector('.create-deal-notifications').appendChild(loadingElement);
            setTimeout(function () {
                loadingElement.classList.add('show');
            }, 20);
            return;
        }

        var oldLoadingElement = loadingElement;
        loadingElement = false;

        oldLoadingElement.classList.remove('show');
        setTimeout(function () {
            document.querySelector('.create-deal-notifications').removeChild(oldLoadingElement);
        }, 150);
    };

    // Display an error notification, or change the current error
    var errorMessage = function (message) {
        if (errorElement) {
            errorElement.querySelector('.error-text').textContent = message;
            return;
        }

        errorElement = document.createElement('div');
        errorElement.className = 'alert alert-danger my-3 create-user-error fade';
        errorElement.innerHTML = '<span class="fe fe-alert-triangle me-3"></span>' +
            '<span class="error-text">' + message + '</span>';
        
        document.querySelector('.create-deal-notifications').appendChild(errorElement);
        setTimeout(function () {
            errorElement.classList.add('show');
        }, 20);
    };

    // Close any displayed error message
    var dismissError = function () {
        if (!errorElement) {
            return;
        }

        var oldErrorElement = errorElement;
        errorElement = false;

        oldErrorElement.classList.remove('show');
        setTimeout(function () {
            document.querySelector('.create-deal-notifications').removeChild(oldErrorElement);
        }, 150);
    };

    // Generates a selection of colours
    var generateHslaColours = function (saturation, lightness, alpha, amount) {
        var colours = [];
        var hueDelta = Math.trunc(360 / amount);
        for (var i = 0; i < amount; i++) {
            var hue = i * hueDelta;
            colours.push('hsla(' + hue + ',' + saturation + '%,' + lightness + '%,' + alpha + ')');
        }
        return colours;
    };

    // Builds colour selection list
    var populateColourList = function () {
        var colours = generateHslaColours(80, 50, 1, 28);

        document.querySelector('.colour-selection-list').innerHTML = colours.map(function (colour) {
            return '<div ' +
                'class="m-3 rounded-circle d-inline-block lift text-center colour-select" ' +
                'data-value="' + colour + '" ' +
                'style="background-color: ' + colour + '; min-height: 48px; min-width: 48px; padding-top: .8rem; cursor: pointer; overflow: hidden;" ' +
            '>' +
                '<div class="d-none"><i class="fe fe-check text-white"></i></div>' +
            '</div>';
        }).join('');

        // Create event handlers for all colour selection items
        document.querySelectorAll('.colour-selection-list .colour-select').forEach(function (element) {
            element.addEventListener('click', function () {
                document.querySelector('[name=colour]').value = element.getAttribute('data-value');
                updateSelectedColour();
            });
        });
    };

    // Update selected colour
    var updateSelectedColour = function () {
        // Hide all check marks, except the one that matches the colour value
        document.querySelectorAll('.colour-selection-list .colour-select').forEach(function (element) {
            element.querySelector('div').classList.add('d-none');
            if (document.querySelector('[name=colour]').value === element.getAttribute('data-value')) {
                element.querySelector('div').classList.remove('d-none');
            }
        });
    };

    // Load agent list
    var populateAgentsList = function () {
        API.request({
            path: '/json/users',
            method: 'get',
            cors: true,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    return;
                }

                document.querySelector('.agent-list').innerHTML = data.users.map(function (user) {
                    if (user.roles.indexOf('Agent') < 0) {
                        return '';
                    }

                    return '<div class="d-flex align-items-center">' +
                        '<div class="mx-1 my-3">' +
                            '<i class="fe fe-user" style="font-size: 200%;"></i>' +
                        '</div>' +

                        '<div class="flex-grow-1 my-3">' +
                            '<h4 class="mb-1 ms-3">' + (user.first_name + ' ' + user.last_name).trim() + '</h4>' +
                        '</div>' +

                        '<div class="text-right d-flex">' +
                            '<div class="flex-grow-1">' +
                                '<input type="number" class="form-control agent-percent" data-agent-id="' + user.id + '" />' +
                            '</div>' +
                            '<div class="p-2 ps-3" style="font-size: 120%;">' +
                                '<span><i class="fe fe-percent"></i></span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                }).join('') + 
                '<hr class="my-4" />' +
                '<div class="d-flex align-items-center">' +
                    '<div class="mx-1 my-3 invisible">' +
                        '<i class="fe fe-user" style="font-size: 200%;"></i>' +
                    '</div>' +

                    '<div class="flex-grow-1 my-3">' +
                        '<h4 class="mb-1 ms-3">Remaining</h4>' +
                    '</div>' +

                    '<div class="text-right d-flex">' +
                        '<div class="flex-grow-1">' +
                            '<input type="number" class="form-control agent-remaining" value="100" disabled="disabled" />' +
                        '</div>' +
                        '<div class="p-2 ps-3" style="font-size: 120%;">' +
                            '<span><i class="fe fe-percent"></i></span>' +
                        '</div>' +
                    '</div>' +
                '</div>';

                // Create event handlers
                document.querySelectorAll('.agent-percent').forEach(function (agentPercent) {
                    agentPercent.addEventListener('keyup', calcRemainingPercent);
                    agentPercent.addEventListener('change', calcRemainingPercent);
                });
            },
        });
    };

    // Calculate the remaining percent available, do not allow more than 100% to be allocated
    var calcRemainingPercent = function () {
        var percentAllocated = 0;

        document.querySelectorAll('.agent-percent').forEach(function (agentPercent) {
            percentAllocated += parseFloat(agentPercent.value !== '' ? agentPercent.value : 0);
        });

        document.querySelector('.agent-remaining').value = 100 - percentAllocated;
    };

    var saveDeal = function () {
        dismissError();
        
        var assignments = {};
        document.querySelectorAll('.agent-percent').forEach(function (element) {
            var agentId = parseInt(element.getAttribute('data-agent-id'));
            assignments[agentId] = element.value;
        });

        var postData = {
            name: document.querySelector('[name=name]').value,
            slug: document.querySelector('[name=name]').value.toLowerCase().replace(/[^a-z0-9- ]/g, '').replace(/[ ]+/g, '-'),
            colour: document.querySelector('[name=colour]').value,
            deduction: document.querySelector('[name=deduction]').value,
            deduction_ivan: document.querySelector('[name=deduction-ivan]').value,
            assignments: assignments,
            enabled: 1,
        };

        loading(true);

        // Execute the request
        API.request({
            path: '/json/deals',
            method: 'post',
            cors: true,
            data: postData,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    return errorMessage('Failed to create deal: ' + data.message);
                }

                location.href = '/deals/';
            },
            error: function () {
                errorMessage('Failed to create deal, server is not responding, please try again.');
            },
            always: function () {
                loading(false);
            },
        });
    };


    populateColourList();
    updateSelectedColour();
    populateAgentsList();

    document.querySelector('.button-create-deal').addEventListener('click', saveDeal);

})();