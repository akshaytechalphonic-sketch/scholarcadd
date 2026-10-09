@include('workflow::workflowTransitions.Lines.extra_js')
@include('layouts.sidebar_modal_js') 
<script>
     var sourceId;
     var source;
     var module_name;

     $(document).ready(function() { 
         let button = $('[data-source-id]').first();
         console.log(button,button.length );

         if (button.length > 0) {
             sourceId = button.attr('data-source-id'); 
             source = button.attr('data-source');
             module_name = button.attr('data-module');
             $('#source_id_upload').val(sourceId);
             $('#source_upload').val(source);
             $('#module_upload').val(module_name);
             doc_workflow_list();
         }

     })

      function doc_workflow_list() {

        $.ajax({
            url: "{{ route('all-workflow-on-doc') }}",
            type: 'Get',
            dataType: 'json',
            data: {
                doc_id: sourceId,
                doc_type: source,
                source:source,
            },

            success: function(response) {
                if (response) {
                    $('.workflow_tab').html('<table class="w-full border-collapse  text-xs">'+
                                '<thead class="bg-primary text-white sticky top-0 z-30">'+
                                    '<tr>'+
                                        '<th class="custome-tables">{{__('auction.ref-code')}} </th>'+
                                        '<th class="custome-tables">{{__('auction.ref-id')}} </th>'+
                                        '<th class="custome-tables">{{__('auction.requested-to')}}</th>'+
                                        '<th class="custome-tables">{{__('auction.assign-date')}}</th>'+
                                        '<th class="custome-tables">{{__('auction.approval-date')}}</th>'+
                                        '<th class="custome-tables">{{__('auction.status')}} </th>'+
                                        '<th class="custome-tables">{{__('auction.action')}} </th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="myTable"> </tbody>'+
                            '</table>');

                    response.forEach(function(indi_data, index) {
                        var button = '';
                        var emp = ''; 
                        var approval_date='' ;
                         if(indi_data['emp_name']){
                            emp = indi_data['emp_name']['name'];
                         }
                         if(indi_data['date']){
                            approval_date = indi_data['date'];
                         }
 
                         if(indi_data['status'] == "Pending" && !approval_date){
                            button = '<button type="button" onclick="view_model_reassign('+indi_data['id']+')" class="dropdown-item reassign-workflow"><i class="mdi mdi-pencil-outline font-size-16 align-middle me-2 text-muted"></i> Re-assign</button>';
                         }

                         var assign = '-';
                         if(indi_data['assign_date']){
                            assign = indi_data['assign_date'];
                         }

                            let listItem = '<tr>'+
                                '<td class="custome-tables">'+indi_data['ref_code']+'</td>'+
                                '<td class="custome-tables">'+indi_data['document_code']+'</td>'+
                                '<td class="custome-tables">'+emp+'</td>'+
                                '<td class="custome-tables">'+assign+'</td>'+
                                '<td class="custome-tables">'+approval_date+'</td>'+
                                '<td class="custome-tables">'+indi_data['status']+'</td>'+
                                '<td class="custome-tables">'+button+'</td>'+
                            '</tr>';

                            $('#myTable').append(listItem);
                    });


                } else {
                    toastr.error(response.message)
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An unexpected error occurred. Please try again later.')
            }
        });
    }
 
 </script>
