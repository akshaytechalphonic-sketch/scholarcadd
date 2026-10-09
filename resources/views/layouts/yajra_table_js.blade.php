$("#applyColumnFilter").on("click", function() {
    const selectedColumn = $("#filterColumn").val();
    if(!selectedColumn)
    {
        toastr.error('First select filter column');
    }
    var custom_filter_new;
    
    var result = selectedColumn.includes("date");    
    if(selectedColumn=='created_at')
    {
        $("#filterValue").val('');
        $("#custom_date").val('');
        var start= $('#startdate').val();
        var end= $('#enddate').val();
        custom_filter_new =[selectedColumn,start,end];    
    }
    else if(result){
        $('#startdate').val('');
        $('#enddate').val('');
        $("#filterValue").val('');
        const filterValue = $("#custom_date").val();
        custom_filter_new =[selectedColumn,filterValue];      
    }
    else{
        $('#startdate').val('');
        $('#enddate').val('');
        $("#custom_date").val('');
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
    $('#custom_date').val('');
    $('#filterValue').prop('disabled', true);;
    $('#startdate').val('');
    $('#enddate').val('');
    $('#applyColumnFilter').prop('disabled', true);
    $('#filterColumn').val('').change();
    $('#custom-date-range').hide();
    $('#custom-date').hide();
    $('#disnone').show();
    custom_filter=null;
    table.draw();
});