<script>
    $(document).ready(function() {
        var main_account = '<?php echo $main_account[1]; ?>';
        var no_of_dim = '<?php echo $val; ?>';
        var tds ='';
        for(var ii=1; ii<=no_of_dim; ii++){
            tds+= "<td> <select name='dim_"+ii+"[]' class='form-control' required> "+main_account+"</select> </td>";
        }
        // name='dim_'"+ii+"

        var deleteIconHTML = `<td style="text-align: center;">
                    <span type="button" class="del-ico" onclick="$.delete_one_html(this)">
                        <i class="ri-delete-bin-line font-size-18 text-danger cursor"></i>
                    </span>
                </td>`;

        $("#add-new-btn-decla").on("click", function() {
            $('#employee-table-decla').append('<tr><td style="text-align: center;"><input type="text" class="form-control" onkeypress="return $.numeric_period();" name="line_no[]" required>'+
                '</td>' +
                '<td><input type="text" value="" name="codes[]" onkeypress="return $.alpha_num_space_period();" maxlength = "60" class="form-control" required/></td>' +
                tds+'<td><input type="text" value="" onkeypress="return $.numeric_period();" name="budget_amount[]" class="form-control" required></td>' +
                ''+deleteIconHTML+'</tr>'
            );
        });

        $.validate = function(){
            var error1 = "Please add at least one line !";
            var arrayof_data = new Array();
            $("input[name='codes[]']").each(function() {
                error1 = "";
            });
            if(error1){
                warning("warning",error1);
                return false;
            }
            return true;
        }
    });
</script>
