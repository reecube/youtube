(function () {
    'use strict';

    var callAjax = function (url, options, callback) {
        if (typeof options !== 'object' || options === null) {
            options = {};
        }

        if (typeof options.method !== 'string') {
            options.method = 'GET';
        }

        if (typeof options.parseJson !== 'boolean') {
            options.parseJson = true;
        }

        var xmlhttp;

        // compatible with IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState !== 4) {
                return;
            }

            if (xmlhttp.status !== 200) {
                return callback(xmlhttp);
            }

            if (options.parseJson) {
                try {
                    return callback(xmlhttp, JSON.parse(xmlhttp.responseText));
                } catch (ex) {
                    console.trace(ex);
                }
            }

            return callback(xmlhttp, xmlhttp.responseText);
        };
        xmlhttp.open(options.method, url, true);

        return xmlhttp.send();
    };

    var links = document.querySelectorAll('.js-lang-link');

    for (var i in links) {
        if (links.hasOwnProperty(i)) {
            links[i].addEventListener('click', function (event) {
                var link = this;

                event.preventDefault();

                return callAjax(link.getAttribute('data-url'), null, function (xmlhttp, result) {
                    if (!result || !result.success) {
                        return console.error(xmlhttp);
                    }

                    return location.reload(true);
                });
            });
        }
    }
})();