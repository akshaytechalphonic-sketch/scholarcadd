@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection

@section('content')
    <div>

        @php echo breadcrums('common.plan',['common.home','common.plan'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('plan-create') }}" class="topbarbtn">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </a>
        @php echo filter_button(); @endphp
    @endsection

</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full  bg-white rounded-md mt-4 border">
    <div class="p-2 ">
        {{-- <div class="overflow-x-auto p-2">
            <table id="states-table" class="display table-auto w-full py-1">
                <thead class="bg-primary text-white text-xs">
                    <tr>
                         <th>Name</th>
                        <th>Recommended</th>
                        <th>Base Discount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-xs">

                </tbody>

            </table>
        </div> --}}







    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 overflow-x-auto">

        @foreach ($plans as $plan)
            @php
                $included = json_decode($plan->included_features, true) ?? [];
                $excluded = json_decode($plan->excluded_features, true) ?? [];
            @endphp

            <div  class="relative bg-white border border-gray-200 shadow-lg rounded-xl p-6 flex flex-col items-center text-center">

                <!-- Action Icons -->
                <div class="absolute top-3 right-3 flex gap-3">

                    <!-- Edit -->
                    <a href="{{ route('plan.edit', encrypt($plan->id)) }}" class="text-blue-600 hover:text-blue-800">
                        <i class="ri-edit-line text-xl"></i>
                    </a>

                    <!-- Delete -->
                    {{-- <form action="{{ route('plan.delete', encrypt($plan->id)) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this plan?')">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="ri-delete-bin-line text-xl"></i>
                        </button>
                    </form> --}}

                    <form action="{{ route('plan.delete', encrypt($plan->id)) }}"
                        method="POST"
                        class="delete-plan-form">
                        @csrf
                        @method('POST') {{-- IMPORTANT if route is DELETE --}}
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="ri-delete-bin-line text-xl"></i>
                        </button>
                    </form>



                </div>

                <!-- Plan Name -->
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $plan->name }}
                </h3>

                <!-- Recommended Badge -->
                @if ($plan->recommended === 'yes')
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-xs font-semibold mb-4">
                        Recommended
                    </span>
                @endif

                <!-- Base Discount -->
                <div class="my-3">
                    <p class="text-sm text-gray-500 font-semibold">Base Discount</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">
                        {{ $plan->baseDiscount }}%
                    </p>
                </div>

                <!-- Divider -->
                <div class="w-full border-t border-gray-200 my-4"></div>

                <!-- Included Features -->
                <div class="w-full mb-4">
                    <p class="text-sm font-semibold text-gray-800 mb-2">Included Features</p>
                    <ul class="space-y-1">
                        @foreach ($included as $item)
                            <li class="flex items-center justify-center gap-2 text-gray-700 text-sm">
                                <i class="ri-check-line text-green-600"></i> {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Excluded Features -->
                <div class="w-full mb-6">
                    <p class="text-sm font-semibold text-gray-800 mb-2">Excluded Features</p>
                    <ul class="space-y-1">
                        @foreach ($excluded as $item)
                            <li class="flex items-center justify-center gap-2 text-gray-700 text-sm line-through">
                                <i class="ri-close-line text-red-600"></i> {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Optional View Button -->
                <a href="{{ route('plan.view', encrypt($plan->id)) }}"
                    class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md mt-auto">
                    View Details
                </a>

            </div>
        @endforeach

    </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-plan-form').forEach(function(form) {

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // stop normal submit

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this plan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit(); // submit after confirmation
                }

            });
        });

    });

});
</script>
















</div>

@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sweetalert_js')
@include('dynamicmaster::bank.table_js')
<script>
    $('#states-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('plans.data') }}',
        columns: [{
                data: 'name',
                name: 'name'
            },
            {
                data: 'recommended',
                name: 'recommended'
            },
            {
                data: 'baseDiscount',
                name: 'baseDiscount'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            } // 👈 must match your addColumn('actions')
        ]
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
                    type: "GET",
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
