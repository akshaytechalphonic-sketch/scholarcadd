@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('common.user-list',['common.home','common.user-list'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{route('users-add')}}"><button type="button"
            class="inline-flex hover-scale gap-1 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </button>
        @php echo filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full  bg-white border rounded-md">
    <div class="p-2 ">
        <div class="overflow-x-auto    p-2">
            <table id="data-table-list" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.sr-no') }}</th>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('common.employee-email') }}</th>
                        <th>{{ __('common.type') }}</th>
                        <th>{{ __('common.role') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('loginer::user.modal')
@endsection

@section('scripts')
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('loginer::user.table_js')
    @include('layouts.sidebar_modal_js')
    @include('loginer::user.extra_js')
    @include('layouts.select2js')

@endsection
