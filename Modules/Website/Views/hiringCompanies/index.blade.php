@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Hiring Companies List'), [__('common.home'), __('website.Hiring Companies List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{route('hiring-companies-create')}}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        {{--  @php echo filter_button(); @endphp  --}}
        <a title="{{ __('Download Excel') }}"
        href="javascript:void(0)"
        onclick="downloadHiringExcel()"
        class="topbarbtn">
            <i class="ri-download-line"></i>
            {{ __('Download Excel') }}
        </a>
    @endsection
</div>
@include('pages.common.custom_filter_html')
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.companyName') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.JobRole') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.jobLink') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.jobDescription') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('website.countryName') }}</th>
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
@include('website::hiringCompanies.table_js')
<script>
function downloadHiringExcel() {


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


    window.open("{{ route('course-hiring-company-excel') }}", "_blank");

    window.history.replaceState(null, '', "{{ route('hiring-companies-list') }}");
}
</script>
@endsection
