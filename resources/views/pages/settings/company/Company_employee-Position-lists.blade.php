@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.select2css')
@endsection
@section('content')
    @php echo breadcrums('Position List',['common.home','Position List'],['dashboard']) @endphp
@section('back_button')
    @php echo back_button('dashboard') @endphp
@endsection
@section('extra_buttons')
    <button type="button" data-target="left-drawer" data-position="right"
        class="open-drawer-btn inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class="ri-add-line text-blue-500 font-semibold"></i>
        {{ __('common.create') }}
    </button>

    @php echo App\Helpers\MasterHelper::filter_button(); @endphp
@endsection
@include('pages.common.custom_filter_html')
<div class="text-xs w-full border bg-white rounded-md">
    <div class="table_wrapper">
        <table id="data-table-list" class="display w-full min-w-full border-collapse">
            <thead class="bg-primary text-white text-xs">
                <tr class="">
                    <th class="px-2 py-1">{{ __('auction.action-id') }}</th>
                    <th class="px-2 py-1">{{ __('auction.auction-title') }}</th>
                    <th class="px-2 py-1">{{ __('auction.start-date-time') }}</th>
                    <th class="px-2 py-1">{{ __('auction.end-date-time') }}</th>
                    <th class="px-2 py-1">{{ __('common.created_by') }}</th>
                    <th class="px-2 py-1">{{ __('common.created_on') }}</th>
                    <th class="px-2 py-1">{{ __('common.status') }}</th>
                    <th class="px-2 py-1">{{ __('common.action') }}</th>
                </tr>
            </thead>
            <tbody class="text-xs">
            </tbody>
        </table>
    </div>
    <div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
    <div id="left-drawer"
        class="drawer fixed z-50 bg-white dark:bg-gray-800 transition-transform hidden w-96 h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-lg shadow-lg flex flex-col"
        tabindex="-1">
        <div class="flex justify-between items-center px-2 py-4 border-b">
            <h5 class="text-base font-semibold text-gray-500 dark:text-gray-400">Add Position </h5>
            <button type="button" class="close-drawer-btn close-btn">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <div class="p-3 max-h-[85vh] overflow-y-auto">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Position Name </label>
                <input class="form-input mt-1 " required>
                </input>
            </div>
            <div class="w-full mt-2">
                <label for="" class=" text-sm font-semibold text-black">Head/Report to name</label>
                <select class="select-single mt-2" style="width: 100%">
                    <option value="">Select an option</option>
                    <option value="Option 1">Option 1</option>
                    <option value="Option 2">Option 2</option>
                    <option value="Option 3">Option 3</option>
                </select>
            </div>

            <div class="w-full mt-2">
                <label for="" class="text-sm font-semibold text-black">Description</label>
                <textarea class="form-input mt-1 " required></textarea>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <button class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i> Close</button>
            <button class="btn-success-sm save-btn"><i class="ri-save-line"></i> Add</button>
        </div>
    </div>

</div>
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.select2js')
@include('layouts.drawerjs')
<script>
    $(document).ready(function() {
        $.append_to_search(['code', 'auction_title'], ['Id', 'Auction Title']);
        var type;
        var custom_filter;
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
            ajax: {
                url: "{{ route('auction-list-yajra') }}",
                type: 'GET',
                data: function(d) {
                    d.filter = type;
                    d.custom_filter = custom_filter;
                },
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{
                    data: 'code',
                    name: 'code',
                },
                {
                    data: 'auction_title',
                    name: 'auction_title',
                },
                {
                    data: 'startdate_time',
                    name: 'startdate_time',
                },
                {
                    data: 'enddate_time',
                    name: 'enddate_time'
                },
                {
                    data: 'creator_name',
                    name: 'creator_name'
                },
                {
                    data: 'create_on',
                    name: 'create_on'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    searchable: false
                }
            ],

            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    className: 'btn btn-green-500 btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    className: 'btn btn-info btn-sm'
                }
            ],
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            pageLength: 10

        });
        $("#applyColumnFilter").on("click", function() {
            const selectedColumn = $("#filterColumn").val();
            if (!selectedColumn) {
                toastr.error('First select filter column');
            }
            var custom_filter_new;
            if (selectedColumn == 'created_at') {
                $("#filterValue").val('');
                var start = $('#startdate').val();
                var end = $('#enddate').val();
                custom_filter_new = [selectedColumn, start, end];
            } else {
                $('#startdate').val('');
                $('#enddate').val('');
                const filterValue = $("#filterValue").val();
                custom_filter_new = [selectedColumn, filterValue];
            }
            if (selectedColumn && (filterValue || (start && end))) {
                custom_filter = custom_filter_new;
                table.draw();
            }
        });
        $('#resetfilter').on('click', function() {
            $('#filterValue').val('');
            $('#filterValue').prop('disabled', true);;
            $('#startdate').val('');
            $('#enddate').val('');
            $('#applyColumnFilter').prop('disabled', true);
            $('#filterColumn').val('').change();
            $('#custom-date-range').hide();
            $('#disnone').show();
            custom_filter = null;
            table.draw();
        });
    });
</script>
@endsection
