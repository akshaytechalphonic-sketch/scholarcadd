dom: 'lBfrtip',
buttons: [{
        extend: 'excelHtml5',
        text: 'Export Excel',
        className: 'btn btn-success btn-sm',
        exportOptions: {
            columns: ':not(:last-child)'
        }
    },
    {
        extend: 'csvHtml5',
        text: 'Export CSV',
        className: 'btn btn-info btn-sm',
        exportOptions: {
            columns: ':not(:last-child)'
        }
    }
],
lengthMenu: [ // Pagination options for Yajra dropdown
    [10, 25, 50, 100 ,500 ,1000],
    [10, 25, 50, 100 , 500 , 1000]
],
pageLength: 100
