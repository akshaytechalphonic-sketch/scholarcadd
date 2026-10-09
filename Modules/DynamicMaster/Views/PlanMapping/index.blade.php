@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>

        @php echo breadcrums('common.plan-mapping',['common.home','common.plan-mapping'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('plan-mapping-create') }}" class="topbarbtn">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </a>
        @php echo filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full  bg-white rounded-md mt-4 border">
    <div class="p-2 ">
        <div class="overflow-x-auto p-2">
            <table id="plan-mapping-table" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>course ID</th>
                        <th>Plan ID</th>
                        {{-- <th>Country ID</th> --}}
                        <th>Course Fee</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Status</th>
                        {{-- <th>Created By</th> --}}
                        {{-- <th>Created At</th> --}}
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
@include('layouts.sweetalert_js')
@include('dynamicmaster::bank.table_js')

{{-- <script>
   $('#plan-mapping-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('plan-mappings.data') }}',
    columns: [
        {
            data: 'DT_RowIndex', // ← Use DT_RowIndex for serial numbers
            name: 'serial',
            orderable: false,
            searchable: false,
             render: function (data, type, row, meta) {
                // Descending serial calculation
                return meta.settings._iRecordsDisplay - meta.row - meta.settings._iDisplayStart;
            }
        },
        {
            data: 'course_name',
            name: 'course.name'
        },
        {
            data: 'plan_name',
            name: 'plan.name'
        },
        {
            data: 'country_name',
            name: 'country.name'
        },
        {
            data: 'course_fee'
        },
        {
            data: 'discount'
        },
        {
            data: 'tax'
        },
        {
            data: 'status'
        },
        {
            data: 'actions',
            orderable: false,
            searchable: false
        }
    ]
});

</script> --}}

<script>
$('#plan-mapping-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('plan-mappings.data') }}',
        data: function (d) {
            // Custom filters can be added here if needed
            // d.custom_filter = ...
        }
    },
    columns: [
        {
            data: 'DT_RowIndex',
            name: 'serial',
            orderable: false,
            searchable: false,
            render: function(data, type, row, meta) {
                return meta.settings._iRecordsDisplay - meta.row - meta.settings._iDisplayStart;
            }
        },
        { data: 'course_name', name: 'course_name' },
        { data: 'course_id', name: 'course_id' },// searchable
        { data: 'plan_name', name: 'plan_name' },     // searchable
        // { data: 'country_name', name: 'country_name', searchable: false },
        { data: 'course_fee', name: 'course_fee' },  // searchable
        { data: 'discount', name: 'discount' },      // searchable
        { data: 'tax', name: 'tax', searchable: false },
        { data: 'status', name: 'status' },          // searchable
        { data: 'actions', orderable: false, searchable: false }
    ],
    order: [[0, 'desc']],
    searchDelay: 500
});

 function deleteRecord(deleteUrl) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: deleteUrl,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {

                        $('#data-table-list').DataTable().draw(false);

                        Swal.fire(
                            'Deleted!',
                            response.message ?? 'Record deleted successfully',
                            'success'
                        );
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Something went wrong while deleting record',
                            'error'
                        );
                    }
                });

            }
        });
    }


</script>


@endsection
