<script>

    $(document).ready(function() {
        $.append_to_search_with_clear(['name'],['Role Name']);


        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,

            ajax: {
                url: "{{ route('role-list-yajra') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type;
                    d.custom_filter= custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },

            columns: [{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', render: function(data) { return data; } },
                    { data: 'total_permissions', render: function(data) { return data; } },
                    { data: 'action', render: function(data) { return data; } },
                ],

            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    className: 'btn btn-info btn-sm'
                }
            ],
            lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
            pageLength: 10
        });

        $("#applyColumnFilter").on("click", function() {

            const selectedColumn = $("#filterColumn").val();
            if(!selectedColumn)
            {
                toastr.error('First select filter column');
            }
            var custom_filter_new;
            if(selectedColumn=='created_at')
            {
                $("#filterValue").val('');
                var start= $('#startdate').val();
                var end= $('#enddate').val();
                custom_filter_new =[selectedColumn,start,end];
            }
            else{
                $('#startdate').val('');
                $('#enddate').val('');
                const filterValue = $("#filterValue").val();
                custom_filter_new =[selectedColumn,filterValue];
            }
            if (selectedColumn && (filterValue || (start && end))) {
                custom_filter = custom_filter_new;
                table.draw();
            }
        });

        $('#resetfilter').on('click', function() {
            $('#filterValue').val('');
            $('#filterValue').prop('disabled', true);;
            $('#startdate').val('');
            $('#enddate').val('');
            $('#applyColumnFilter').prop('disabled', true);
            $('#filterColumn').val('').change();
            $('#custom-date-range').hide();
            $('#disnone').show();
            custom_filter=null;
            table.draw();
        });
    });

</script>
