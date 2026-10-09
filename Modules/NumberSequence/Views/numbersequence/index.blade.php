@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('common.numbersequence',['common.home','common.numbersequence'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('numbersequence-add') }}">
            <button type="button" class="topbarbtn">
                <i class=" ri-add-line text-blue-500 font-semibold"></i>
                {{ __('common.add') }}
            </button>
        </a>
        @php echo filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full table_wrapper p-2">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
            <thead class="bg-primary text-white text-xs">
                <tr>
                    <th>{{ __('common.numbersequencecode') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.constant') }}</th>
                    <th>{{ __('common.range') }}</th>
                    <th>{{ __('common.format') }}</th>
                    <th>{{ __('common.mapped') }}</th>
                    <th>{{ __('common.created_by') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
            </thead>
            <tbody class="text-xs">
            </tbody>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('numbersequence::numbersequence.table_js')
@endsection
