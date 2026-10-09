@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Alumni List'), [__('common.home'), __('Course.Alumni List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{ route('course-company-alumni-create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        <a title="{{ __('Download Excel') }}"
        href="javascript:void(0)"
        onclick="downloadStateExcel()"
        class="topbarbtn">
            <i class="ri-download-line"></i>
            {{ __('Download Excel') }}
        </a>
        {{--  @php echo filter_button(); @endphp  --}}
    @endsection
</div>
@include('pages.common.custom_filter_html')
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>

                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.CompanyName') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.CompanyLink') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.CountryName') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.ISO2') }}</th>

                    <th class="px-4 py-3 whitespace-nowrap">{{ __('common.created_at') }}</th>

                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Actions') }}</th>
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
{{-- @include('Course::CertificateText.extra_js') --}}
@include('Course::CompanyAlumni.table_js')
<script>
    function downloadStateExcel() {


    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    Toast.fire({
        icon: 'success',
        title: 'Download started'
    });


    window.open("{{ route('course-alumni-excel') }}", "_blank");

    window.history.replaceState(null, '', "{{ route('course-company-alumni-list') }}");
}
</script>
@endsection
