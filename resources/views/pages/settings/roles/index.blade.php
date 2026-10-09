@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
    @php echo breadcrums('company.role-list',['common.home','company.role-list'],['dashboard']) @endphp
    @section('back_button') @php echo back_button('employee-dashboard') @endphp @endsection
    @section('extra_buttons')
    <a href="{{route('create-role')}}"
        class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{__('common.add')}}
    </a>
    @php echo App\Helpers\MasterHelper::filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full border bg-white rounded-md p-2">
    <div class="table_wrapper">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.sr-no') }}</th>
                        <th>{{ __('common.role-name') }}</th>
                        <th>{{ __('common.total-permission') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                {{-- <tbody class="text-xs">

                </tbody> --}}

        </table>
    </div>
</div>
@endsection



@section('scripts')


    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('pages.settings.roles.table_js')
    @include('components.formvalidationjs')
    @include('pages.settings.roles.extra_js')




@endsection
