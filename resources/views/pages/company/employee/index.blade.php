@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>

        @php echo breadcrums('company.emp-head',['common.home','company.emp-head'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('employee-add') }}"
            class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </a>
        @php echo App\Helpers\MasterHelper::filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full  bg-white rounded-md mt-4">
    <div class="  p-2 ">
        <div class="overflow-x-auto p-2">
            <table id="data-table-list" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr> 
                        <th>{{ __('company.emp-code') }}</th>
                        <th>{{ __('common.names') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('common.Department') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.action') }}</th>

                    </tr>
                </thead>
                <tbody class="text-xs">

                </tbody>

            </table>
        </div>
    </div>
</div>
 
@include('pages.company.employee.modal') 

@endsection
@section('scripts')
 
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('pages.company.employee.table_js')
@include('layouts.select2js')


<script>
    function togglview_modelSidebar(doc_id,name,code) {
        const sidebar = document.getElementById('view-sidebar');
        sidebar.classList.toggle('translate-x-full');          
            $('#emp_id').val(doc_id);
            $('#emp_name').val(name);            
            $('#emp_code').val(code);          
       }
</script>

@endsection
