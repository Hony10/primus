/**
 * API handler.
 * 
 * Manages communication to APIs and services.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

var API = function() {
    'use strict';

    var _serialize = function(obj) {
        if (!obj || (typeof(obj) !== 'object') || (obj.length === 0)) {
            return '';
        }
        var queryParts = [];
        Object.keys(obj).forEach(function(key) {
            if (Array.isArray(obj[key])) {
                for (var i = 0; i < obj[key].length; i++) {
                    queryParts.push(key + '"' + encodeURIComponent(obj[key][i]));
                }
            } else {
                queryParts.push(key + '=' + encodeURIComponent(obj[key]));
            }
        });
        return queryParts.join('&');
    };

    var _request = function(requestData) {
        var path = requestData.path;
        var data = requestData.data;

        // Prepare data for the request
        if (!requestData.method || (requestData.method === 'get') || (requestData.method === 'delete')) {
            var serialData = _serialize(requestData.data);
            path += (serialData === '') ? '' : '?' + serialData;
            data = undefined;
        } else {
            data = typeof(data) === 'object' ? JSON.stringify(data) : data;
        }

        var headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };

        // Execute the request now
        fetch(path, {
            method: requestData.method ? requestData.method.toUpperCase() : 'GET',
            mode: requestData.cors ? 'cors' : 'no-cors',
            credentials: requestData.cors ? 'include' : undefined,
            cache: 'no-cache',
            headers: requestData.headers ? Object.assign(headers, requestData.headers) : headers,
            redirect: 'follow',
            referrer: 'no-referrer',
            body: data,
        })
        .then(function(response) {
            // If response body is empty, return an empty data object
            if (!response.body) {
                if (typeof(requestData.success) === 'function') {
                    requestData.success(response.status, {});
                }
                if (typeof(requestData.always) === 'function') {
                    requestData.always(response.status, {});
                }
                return;
            }

            // Convert JSON to object and return to callbacks
            var jsonOk = false;
            var textResponse = response.clone();
            response.json().then(function (data) {
                jsonOk = true;
                if (typeof(requestData.success) === 'function') {
                    requestData.success(response.status, data);
                }
                if (typeof(requestData.always) === 'function') {
                    requestData.always(response.status, data);
                }
            }).catch(function (_) {
                if (!jsonOk) {
                    // JSON failed, return plain text
                    textResponse.text().then(function (data) {
                        if (typeof(requestData.success) === 'function') {
                            requestData.success(textResponse.status, data);
                        }
                        if (typeof(requestData.always) === 'function') {
                            requestData.always(textResponse.status, data);
                        }
                    });
                }
            });
        })
        .catch(function() {
            if (typeof(requestData.error) === 'function') {
                requestData.error();
            }
            if (typeof(requestData.always) === 'function') {
                requestData.always();
            }
        });
    };

    // Expose public functions
    return {
        request: _request
    };

}();