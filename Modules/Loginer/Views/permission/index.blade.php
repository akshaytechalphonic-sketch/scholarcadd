
 @extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>        
    @php echo breadcrums('common.permission-list',['common.home','common.permission-list'],['dashboard']) @endphp 
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection        
    @section('extra_buttons')
    <button type="button" onclick="toggle_model('add-sidebar')"
        class="inline-flex hover-scale gap-1 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{ __('common.add') }}
    </button>
    @php echo filter_button(); @endphp
    @endsection
 
</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full  bg-white rounded-md mt-4">
    <div class="  p-2 ">
         
        <div class="overflow-x-auto    p-2">
            <table id="data-table-list" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.sr.no') }}</th>
                        <th>{{ __('company.permission-name') }}</th> 
                        <th>{{ __('company.action') }}</th>                         
                    </tr>
                </thead>
                <tbody class="text-xs">                  
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.create-permission') }}
        </h5>
        <button type="button" onclick="toggle_model('add-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <div class="w-full">
            <label class="text-sm">{{ __('common.permission') }}</label>
            <input type="text" id="name" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div> 
        
  
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggle_model('add-sidebar')" class="btn-primary-sm">
                    <i class="ri-close-circle-line"></i>
                    {{ __('common.close') }}
                </button>
                <button type="button" class=" master_add btn-success-sm">
                    <i class="ri-add-circle-line"></i>
                    {{ __('common.add') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('loginer::permission.table_js')   
    @include('layouts.sidebar_modal_js')
    <script>
        $(document).ready(function() {  
        
        $(".drawer-overlay").on('click', function(){ 
            closeAllSidebars('add-sidebar'); 
        });

    });


    $(document).on('click', '.master_add', function() { 
            var name = $('#name').val(); 
            if (name) {
                  
                    var url = "{{route('permissions-store')}}";
                    showLoader();
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: name 
                        },
                        cache: false,
                        //   async: false,
                        success: function(data) {
                            hideLoader();
                            if (data.status) {
                                toastr.success(data.success);
                                setTimeout(function () {
                                    window.location.href = "{{ route('permissions-list') }}"; 
                                }, 1500);
                                table.draw();
                                $('#name').val(''); 
                                toggle_model('add-sidebar');
                            } else
                                toastr.error(data.error);
                        },
                       error: function(xhr) {
                            hideLoader();
                            if (xhr.status === 400) {
                                let response = JSON.parse(xhr.responseText);
                                toastr.error(response.error);
                            } 
                            else if (xhr.status === 422) { 
                                let response = JSON.parse(xhr.responseText);

                                if (response.type === 'validation' && response.error) { 
                                    $.each(response.error, function(key, messages) {
                                        if (Array.isArray(messages)) {
                                            messages.forEach(function(msg) {
                                                toastr.error(msg);
                                            });
                                        } else {
                                            toastr.error(messages);
                                        }
                                    });
                                } else {
                                    toastr.error("Validation failed.");
                                }
                            } 
                            else {
                                toastr.error("An unexpected error occurred.");
                            }
                        }

                    });
                 

            } else {
                toastr.warning("Please fill permission name");
            }
        });

    </script>
    
@endsection