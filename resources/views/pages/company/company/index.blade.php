@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        
    @php echo breadcrums('company.company-list',['common.home','company.company-list'],['dashboard']) @endphp 
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection        
    @section('extra_buttons')
    <a href="{{route('company-add')}}"
        class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{__('common.add')}}
    </a>
    @php echo App\Helpers\MasterHelper::filter_button(); @endphp
    @endsection
 
</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full  bg-white rounded-md mt-4">
    <div class="  p-2 ">
         
        <div class="overflow-x-auto    p-2">
            <table id="data-table-list" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.code') }}</th>
                        <th>{{ __('company.company-name') }}</th>
                        <th>{{ __('company.com-regitration-no') }}</th>
                        <th>{{ __('company.vat-no') }}</th>
                        <th>{{ __('company.company-date') }}</th>
                        <th>{{ __('company.user-name') }}</th>
                        <th>{{ __('company.status') }}</th>
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
    @include('pages.company.company.table_js')
    
@endsection