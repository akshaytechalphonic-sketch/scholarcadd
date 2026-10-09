@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
    @php echo breadcrums('email.email-template-list',['common.home','email.email-template-list'],['dashboard']) @endphp
    @section('back_button') @php echo back_button('dashboard') @endphp @endsection
    @section('extra_buttons')
    <a href="{{route('email-template-create')}}"
        class="topbarbtn">
        <i class=" ri-add-line text-blue-500 font-semibold"></i>
        {{__('common.add')}}
    </a>
    @php echo filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full border bg-white rounded-md p-2">
    <div class="table_wrapper">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th class="text-center">{{ __('common.sr-no') }}</th>
                        <th>{{ __('email.email-master') }}</th>
                        <th>{{ __('email.subject') }}</th>
                        <th>{{ __('common.created-by') }}</th>
                        <th>{{ __('common.created-at') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead> 
        </table>
    </div>
</div>
@endsection



@section('scripts')


    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('dynamicmaster::emailtemplate.table_js')  
    @include('components.formvalidationjs') 




@endsection
