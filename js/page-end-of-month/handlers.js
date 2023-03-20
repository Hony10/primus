(function () {
    'use strict';

    document.querySelector('[name=filter-month]').addEventListener('change', function () {
        document.querySelector('.end-of-month-title').innerHTML = document.querySelector('[name=filter-month]').value.toUpperCase();
        pageRefresh();
    });

})();