(function () {
    'use strict';


    // What columns do we want to export from the data set by default
    var exportColumns = [
        {name: 'date', label: 'Date'},
        {name: 'customer_name', label: 'Name'},
        {name: 'mortgage_property', label: 'Property'},
        {name: 'mk', label: 'MK'},
        {name: 'product', label: 'Product'},
        {name: 'deal_name', label: 'Deal'},
        {name: 'commission_value', label: 'Value'},
    ];

    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


    // Export current data set to CSV file and download. You can specify a custom configuration for the export
    var exportCommissions = function (exportConfiguration) {
        // If no data to export, take no action
        if (!Commissions.data || (Commissions.data.length <= 0)) {
            return;
        }

        var rows = [];

        var columns = (typeof(exportConfiguration) === 'object') && Array.isArray(exportConfiguration) ? exportConfiguration : exportColumns;

        // Create the header row
        rows.push(columns.map(function (column) {
            return column.label;
        }));

        // Create all data rows
        Commissions.data.forEach(function (commission) {
            var newRow = [];
            columns.forEach(function (column) {
                newRow.push(commission[column.name]);
            });
            rows.push(newRow);
        });

        // Encode the export data
        var csvContent = 'data:text/csv;charset=utf-8,' + rows.map(function (row) {
            return row.join(',');
        }).join("\n");

        // Generate the filename for the export
        var dateFrom = new Date(document.querySelector('[name=filter-date-from]').value);
        var dateTo = new Date(document.querySelector('[name=filter-date-to]').value);
        var filename = document.querySelector('.agent-selected-preview > span').textContent + ' ' +
            months[dateFrom.getMonth()].toUpperCase() + dateFrom.getFullYear().toString().substr(-2) +
            '-' +
            months[dateTo.getMonth()].toUpperCase() + dateTo.getFullYear().toString().substr(-2) +
            '.csv';

        // Get browser to download a file
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();

        // Clean up DOM after download has completed
        setTimeout(function () {
            document.body.removeChild(link);
        }, 2000);
    };


    // Create event handlers if elements are available
    if (document.querySelector('.commissions-export')) {
        document.querySelector('.commissions-export').addEventListener('click', exportCommissions);
    }


    // Public methods and properties
    Commissions.export = exportCommissions;

})();