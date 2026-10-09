@include('layouts.sweetalert_js')
<script>    
    $(document).ready(function() {        
        $.apply_workflow = function(document_type, doc_id,source=null) {            
            if (doc_id) {
                Swal.fire({
                title: 'Are you sure for submission?',
                text: "For submit !",
                timer: 10000,
                icon: 'warning',
                showCancelButton: true, 
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes !'
            }).then((result) => {
                if (result.isConfirmed) {
                   showLoader();
                    $.ajax({
                    type: "POST",
                    url: "{{ route('document-apply-wf') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        document_type: document_type,
                        doc_id: doc_id,
                        source: source,
                    },
                    cache: false,
                    //   async: false,
                    success: function(Result) {
                        hideLoader();
                        if (Result[0]) {
                            toastr.success(Result[1]);
                            if (document_type == 'Purchase Request')
                                window.location.href = "{{ route('login') }}";

                             
                            location.reload(); 
                        } else {
                            toastr.error(Result[1]);  
                        }
                    }
                });
                }
            })

            } else {
                
                toastr.warning("Save data first"); 
            }
        }
    });
</script>