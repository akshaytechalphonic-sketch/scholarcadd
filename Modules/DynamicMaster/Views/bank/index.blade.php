@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>

    @php echo breadcrums('common.bank-list',['common.home','common.bank-list'],['dashboard']) @endphp
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection
    @section('extra_buttons')
    <a href="{{route('bank-create')}}"
        class="topbarbtn">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{__('common.add')}}
    </a>
    @php echo filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full  bg-white rounded-md mt-4 border">
    <div class="p-2 ">
        <div class="overflow-x-auto p-2">
            <table id="data-table-list" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>{{ __('common.Name') }}</th>
                         <th>{{ __('common.Bank Group') }}</th>
                        <th>{{ __('common.Account No') }}</th>
                        <th>{{ __('common.Country') }}</th>
                        <th>{{ __('common.City') }}</th>
                        <th>{{ __('common.Bank Main Account') }}</th>
                        <th>{{ __('common.phone-number') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('common.Contact Name') }}</th>
                        <th>{{ __('common.Currency') }}</th>
                        <th>{{ __('common.Reconciliation') }}</th>
                        <th>{{ __('common.Status') }}</th>
                        <th>{{ __('common.Action') }}</th>
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
    @include('dynamicmaster::bank.table_js')
@endsection
