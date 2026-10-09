<script>
            function get_variables(id) {
                var email_master_id = '';
                if(id == 0){
                    email_master_id = document.getElementById('email_master_id').value;
                }
                else{
                    email_master_id = id;
                }
                // $('#veriable').val('');

                if(email_master_id){
                    showLoader();
                    $.ajax({
                        url: "{{ route('get-email-master') }}",
                        method: 'POST',
                        data: {
                            email_master_id: email_master_id,
                            _token: '{{ csrf_token() }}'
                        },
                        cache: false,
                        //   async: false,
                        success: function(dataResult) {
                            hideLoader();
                            var dataResult = JSON.parse(dataResult);
                            $('#veriable').val(dataResult.variables);
                            if(dataResult.statusCode != 200){
                                error("error","Something went wrong");
                                $('#veriable').val('');
                            }
                        }
                    });
                }
                else{
                    $('#veriable').val('');
                }
            }

            var old_email_master_id = "<?php echo $email_template?->email_master_id ?>";
            if(old_email_master_id > 0){
                get_variables(old_email_master_id);
            }


</script>
