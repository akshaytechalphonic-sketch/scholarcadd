
 @extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>        
    @php echo breadcrums('common.role-list',['common.home','common.role-list'],['dashboard']) @endphp 
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection        
    @section('extra_buttons')
    <a href="{{route('roles-create')}}"
        class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{__('common.add')}}
    </a>
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
                        <th>{{ __('company.role-name') }}</th>
                        <th>{{ __('common.total-permission') }}</th>
                        <th>{{ __('company.action') }}</th>                         
                    </tr>
                </thead>
                <tbody class="text-xs">                  
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')

    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('loginer::role.table_js')     
@endsection