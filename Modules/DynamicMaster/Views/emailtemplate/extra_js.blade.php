<script>


            $('#email_master_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var master_parent_id = selectedOption.attr('data-email_temp_id'); // or .data('email_temp_id')
                
                if(master_parent_id){
                    showLoader();
                    $.ajax({
                        url: "{{ route('master-parent-edit') }}",
                        method: 'GET',
                        data: {
                            master_parent_id: master_parent_id,
                            _token: '{{ csrf_token() }}'
                        },
                        cache: false,
                        //   async: false,
                        success: function(data) {
                            hideLoader();
                            if(data.status){
                                $('#veriable').val(data.data.description);
                                $('#name').val(data.data.name);
                            }
                            else{
                                $('#veriable').val('');
                                $('#name').val('');
                            }
                        }
                    });
                }
                else{
                    $('#veriable').val('');
                }
                
            });

            // $(document).ready(function(){
            //     var master_parent_id = $('#email_master_id').val();
            //     if(master_parent_id){
            //         $('#email_master_id').trigger('change');
            //     }
            // });




            // var old_email_master_id = "<?php echo $data?->email_master_id ?>";
            // if(old_email_master_id > 0){
            //     get_variables(old_email_master_id);
            // }


</script>
