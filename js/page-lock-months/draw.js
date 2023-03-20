/**
 * Lock Months Draw JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

var PageDraw = function (drawData) {
    'use strict';

    var months = [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC' ];

    var yearDisplay = function () {
        return '<div class="row text-center fs-1">' +
            '<div class="col-4">' +
                '<button class="btn btn btn-rounded-circle btn-white year-prev">' +
                    '<i class="fe fe-chevron-left"></i>' +
                '</button>' +
            '</div>' +
            '<div class="col-4 fw-bold">' +
                document.querySelector('[name=selected-year]').value +
            '</div>' +
            '<div class="col-4">' +
                '<button class="btn btn btn-rounded-circle btn-white year-next">' +
                    '<i class="fe fe-chevron-right"></i>' +
                '</button>' +
            '</div>' +
        '</div>';
    };

    var month = function (drawMonth) {
        return '<div class="col-12 col-md-6 col-lg-4">' +
            '<div class="card">' +
                '<div class="card-body' +
                    (!drawMonth.locked && drawMonth.payments > 0 ? ' bg-primary text-white' : '') +
                    (drawMonth.locked && drawMonth.payments > 0 ? ' bg-danger text-white' : '') +
                    (drawMonth.payments <= 0 ? ' bg-white' : '') +
                    '" ' +
                    'style="' + (drawMonth.payments <= 0 ? 'opacity: 0.5;' : '') + '" ' +
                '>' +
                    '<div class="row">' +
                        '<div class="col-8">' +
                            '<div style="font-size: 120%;">' +
                                months[parseInt(drawMonth.date.split(/[-]/g).slice(1, 2)) - 1] + ' ' +
                            '</div>' +
                            '<div>' +
                                drawMonth.payments + ' ' +
                                'payment' + (drawMonth.payments === 1 ? '' : 's') +
                            '</div>' +
                        '</div>' +
                        '<div class="col-4 text-end">' +
                            (drawMonth.locked ? (
                                '<button class="btn btn-rounded-circle btn-danger" disabled="disabled" title="This month has been locked" data-bs-toggle="tooltip">' +
                                    '<span class="fe fe-lock"></span>' +
                                '</button>'
                            ) : (
                                '<button ' +
                                    'class="btn btn-rounded-circle btn-white month-lock" ' +
                                    'data-bs-toggle="tooltip" ' +
                                    'data-month="' + (parseInt(drawMonth.date.split(/[-]/g).slice(1, 2)) - 1) + '" ' +
                                    (drawMonth.payments <= 0 ? 'disabled="disabled" ' : '') +
                                    (drawMonth.payments <= 0 ? 'title="Cannot lock this month, no payments have been made" ' : '') +
                                    (drawMonth.payments > 0 ? 'title="Lock this month" ' : '') +
                                '>' +
                                    '<span class="fe fe-unlock"></span>' +
                                '</button>'
                            )) +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    };


    var createHandlers = function () {
        var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        document.querySelectorAll('.months-list .month-lock').forEach(function (element) {
            element.addEventListener('click', function () {
                document.querySelector('.modal-month').innerHTML = months[element.getAttribute('data-month')];
                document.querySelector('.modal-year').innerHTML = document.querySelector('[name=selected-year]').value;
                document.querySelector('.modal-date').value = document.querySelector('[name=selected-year]').value + '-' + (parseInt(element.getAttribute('data-month')) + 1).toString().padStart(2, '0') + '-01';
                myModal.show();
            });
        });

        document.querySelectorAll('.months-list [data-bs-toggle=tooltip]').forEach(function (element) {
            new bootstrap.Tooltip(element);
        });

        document.querySelector('.year-prev').addEventListener('click', function () {
            document.querySelector('[name=selected-year]').value = parseInt(document.querySelector('[name=selected-year]').value) - 1;
            PageRefresh();
        });
        document.querySelector('.year-next').addEventListener('click', function () {
            document.querySelector('[name=selected-year]').value = parseInt(document.querySelector('[name=selected-year]').value) + 1;
            PageRefresh();
        });
    };


    document.querySelector('.months-list').innerHTML = yearDisplay() +
        '<div class="row mt-4">' +
            drawData.map(function (monthData) {
                return month(monthData);
            }).join('') +
        '</div>';
    createHandlers();
};