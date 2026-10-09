@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>

        @php echo breadcrums('State List',['common.home','State List'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('bank-create') }}" class="topbarbtn">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </a>
        {{--  @php echo filter_button(); @endphp  --}}
    @endsection

</div>
@include('pages.common.custom_filter_html')

<div class="text-xs w-full  bg-white rounded-md mt-4 border">
    <div class="p-2 ">
        <div class="overflow-x-auto p-2">
            <table id="states-table" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Country Code</th>
                        <th>ISO2</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Status</th>
                        <th>Action</th>
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
<script>
    $('#states-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('states.data') }}',
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'country_code',
                name: 'country_code'
            },
            {
                data: 'iso2',
                name: 'iso2'
            },
            {
                data: 'latitude',
                name: 'latitude'
            },
            {
                data: 'longitude',
                name: 'longitude'
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            } // 👈 must match your addColumn('actions')
        ]
    });
</script>
@endsection
