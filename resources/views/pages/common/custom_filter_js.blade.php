
<script>
    $(document).ready(function () {
    const filterValue = $("#filterValue");
    const applyColumnFilter = $("#applyColumnFilter");
    const customDateRange = $("#custom-date-range");
    const customDate = $("#custom-date");
    const disnone = $("#disnone");

    // Initial setup
    filterValue.prop("disabled", true);
    applyColumnFilter.prop("disabled", true);
    customDateRange.hide();
    customDate.hide();

    $("#filterColumn").on("change", function () {
        const val = $(this).val();
        if (val) {
            const result = val.includes("date");

            if (val === "created_at") {
                customDateRange.show();
                disnone.hide();
                customDate.hide();
            } else if (result) {
                customDate.show();
                customDateRange.hide();
                disnone.hide();
            } else {
                disnone.show();
                customDate.hide();
                customDateRange.hide();
                filterValue.prop("disabled", false);
            }
        } else {
            filterValue.prop("disabled", true);
            applyColumnFilter.prop("disabled", true);
            disnone.show();
            customDateRange.hide();
            customDate.hide();
        }
    });

    filterValue.on("input paste", function () {
        setTimeout(function () {
            const val = filterValue.val().trim();
            applyColumnFilter.prop("disabled", val === "");
        }, 0);
    });

    $("#enddate").on("changeDate input", function () {
        const val = $(this).val().trim();
        applyColumnFilter.prop("disabled", val === "");
    });

    $("#startdate").on("input", function () {
        const val = $(this).val().trim();
        applyColumnFilter.prop("disabled", val === "");
    });

    $("#custom_date").on("input", function () {
        const val = $(this).val().trim();
        applyColumnFilter.prop("disabled", val === "");
    });

    $(".fil-btn").click(function () {
        $(".custom-filter-card").toggle(800);
    });
});


    // $(document).ready(function() {
        
    //     const filterColumn = document.getElementById("filterColumn");
    //     const filterValue = document.getElementById("filterValue");
    //     const disnone = document.getElementById("disnone");
    //     const enddate = document.getElementById("startdate");
    //     const custom_date = document.getElementById("custom_date");
    //     const applyColumnFilter = document.getElementById("applyColumnFilter");
    //     const customDateRange = document.getElementById("custom-date-range");
    //     const customDate = document.getElementById("custom-date");
        
    //     filterValue.disabled = true;
    //     applyColumnFilter.disabled = true;
    //     customDateRange.style.display = "none";
    //     customDate.style.display = "none";

    //     filterColumn.addEventListener("change", function() {
    //         if (filterColumn.value) {                
    //             var result = filterColumn.value.includes("date");
                
    //             if (filterColumn.value === 'created_at') {
    //                 customDateRange.style.display = "block";
    //                 disnone.style.display = "none";
    //                 customDate.style.display = "none";
    //             } 
    //             else if(result){
    //                 customDate.style.display = "block";
    //                 customDateRange.style.display = "none";
    //                 disnone.style.display = "none";
    //             }
    //             else {
    //                 disnone.style.display = "block";
    //                 customDate.style.display = "none";
    //                 customDateRange.style.display = "none";
    //                 filterValue.disabled = false; // Enable the input field
    //             }
    //         } else {
    //             filterValue.disabled = true;
    //             applyColumnFilter.disabled = true;
    //             disnone.style.display = "block"; // Show the input field
    //             customDateRange.style.display = "none"; // Hide the date picker
    //             customDate.style.display = "none";
    //         }
    //     });
    //     filterValue.addEventListener("input", function() {
    //         if (filterValue.value.trim() !== "") {
    //             applyColumnFilter.disabled = false;
    //         } else {
    //             applyColumnFilter.disabled = true;
                
    //         }
    //     });

    //     // Listen for the paste event as well
    //     filterValue.addEventListener("paste", function() {
    //         // Delay check to ensure paste operation is complete
    //         setTimeout(function() {
    //             if (filterValue.value.trim() !== "") {
    //                 applyColumnFilter.disabled = false;
    //             } else {
    //                 applyColumnFilter.disabled = true;
    //             }
    //         }, 0);
    //     });
 
    //     $('#enddate').on('changeDate', function(e) {
    //         const endDate = $('#enddate').val();
    //         if (endDate.trim() !== "") {
    //             applyColumnFilter.disabled = false;
    //         } else {
    //             applyColumnFilter.disabled = true;
    //         }
    //     });

    //     $('#enddate').on('input', function(e) {
    //         const endDate = $('#enddate').val();
    //         if (endDate.trim() !== "") {
    //             applyColumnFilter.disabled = false;
    //         } else {
    //             applyColumnFilter.disabled = true;
    //         }
    //     });
        
    //     $('#startdate').on('input', function(e) {
    //         const endDate = $('#startdate').val();
    //         if (endDate.trim() !== "") {
    //             applyColumnFilter.disabled = false;
    //         } else {
    //             applyColumnFilter.disabled = true;
    //         }
    //     });

    //     $('#custom_date').on('input', function(e) {
    //         const custom_date = $('#custom_date').val();
    //         if (custom_date.trim() !== "") {
    //             applyColumnFilter.disabled = false;
    //         } else {
    //             applyColumnFilter.disabled = true;
    //         }
    //     });
        


    //     $(".fil-btn").click(function () {
    //         $(".custom-filter-card").toggle(800);
    //     });
    // });

 
    $.append_to_search = function(col_name,col_disp_name){
        for(var j=0; j<col_name.length; j++){ 
            $('#filterColumn option:eq(1)').before('<option value="' + col_name[j] + '">' + col_disp_name[j] + '</option>');
        }
    }

    $.append_to_search_with_clear = function(col_name,col_disp_name){
        $('#filterColumn').html('<option value="" disabled selected>Select Filter</option>');
        for(var j=0; j<col_name.length; j++){
            $('#filterColumn').append('<option value="' + col_name[j] + '">' + col_disp_name[j] + '</option>');
        }
    }
 
     
  

</script>