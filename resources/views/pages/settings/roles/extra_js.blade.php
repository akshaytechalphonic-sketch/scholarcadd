<script>
    $(document).ready(function() {
        $("#multiple-menus").change(function() {
            var ids = $(this).val();
            showLoader();
            $.ajax({
                url: "{{ route('role-menusetting') }}",
                type: "get",
                data: {
                   // _token: '{{ csrf_token() }}',
                    ids: ids,
                },
                cache: false,
                //   async: false,
                success: function(response) {
                    hideLoader();
                    $('#permissions_list').html('');
                    $.each(response, function(index, data) {
                        console.log(data);

                        $('#permissions_list').append(
                            '<tr><td class="py-2"><input type="checkbox" class="checkone" value=' + data?.id + ' name="permission[]"></td>' +
                            '<td class="py-2">' + data?.name + '</td><td>' + data?.menu_details?.menuname + '</td></tr>');
                    });
                }
            });
        });
        $("#selectAll").click(function() {

            $(".checkone").prop('checked', $(this).prop('checked'));
        });


        $(".checkone").click(function() {

            if ($(".checkone:checked").length === $(".checkone").length) {
                $("#selectAll").prop('checked', true);
            } else {
                $("#selectAll").prop('checked', false);
            }
        });


        $("#selectAll").change(function() {
            if (!$(this).prop('checked')) {
                $(".checkone").prop('checked', false);
            }
        });
    });
</script>
