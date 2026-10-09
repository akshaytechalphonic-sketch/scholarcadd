@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('All Sections'), [__('common.home'), __('All Sections')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{ route('landingpage.create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        @php echo filter_button(); @endphp
    @endsection
</div>
@include('pages.common.custom_filter_html')
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="sectionTable" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    {{-- <th class="px-4 py-3 whitespace-nowrap">ID</th> --}}
                    <th class="px-4 py-3 whitespace-nowrap">Section</th>
                    <th class="px-4 py-3 whitespace-nowrap">Label</th>
                    <th class="px-4 py-3 whitespace-nowrap">Heading</th>
                    <th class="px-4 py-3 whitespace-nowrap">Description</th>
                    <th class="px-4 py-3 whitespace-nowrap">Button Label</th>
                    <th class="px-4 py-3 whitespace-nowrap">Created At</th>
                    <th class="px-4 py-3 whitespace-nowrap">Images</th>
                    <th class="px-4 py-3 whitespace-nowrap">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
</section>
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sweetalert_js')
@include('Course::Master.CourseCategory.extra_js')
@include('Course::Master.CourseCategory.table_js')

<script>
    $(function() {
        $('#sectionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('section.data.ajax') }}",
            columns: [
                // {
                //     data: 'id',
                //     name: 'id'
                // },
                {
                    data: 'section',
                    name: 'section'
                },
                {
                    data: 'label',
                    name: 'label'
                },
                {
                    data: 'heading',
                    name: 'heading'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'button_label',
                    name: 'button_label'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'images',
                    name: 'images',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'actions',
                    name: 'actions'
                },

            ]
        });
    });
</script>
@endsection
