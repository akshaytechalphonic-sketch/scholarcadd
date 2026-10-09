@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
    @include('layouts.select2css')
    <style>
        span.select2-dropdown.select2-dropdown--below {
            height: 430px;
        }

        .select2-container--default .select2-results>.select2-results__options {
            max-height: 380px !important;
        }
    </style>
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Course List'), [__('common.home'), __('Course.Course List')], ['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <a title="{{ __('Course.Add') }}" href="{{ route('dynamic-course-pages-create') }}" class="topbarbtn">
            <i class="ri-add-line"></i>
            {{ __('Course.Add') }}
        </a>
        {{-- @php echo filter_button(); @endphp --}}
<iframe id="downloadFrame" style="display:none;"></iframe>

<button type="button" onclick="startDownload()" class="topbarbtn cursor-pointer">
    <i class="ri-refresh-line mr-1"></i>
    Download Excel
</button>
    @endsection

</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-1">
    <div class="col-span-1">
        <select class="select-single" style="width:100%" id="courseDate">
            <option value="" selected>Select Course Title</option>
            @foreach ($courseTitles as $title)
                <option value="{{ $title->course_title }}">{{ $title->course_title }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-span-1">
        <select class="select-single " style="width:100%" id="CourseCountryName">
            <option value="" selected>Select Country</option>
            @foreach ($countries as $country)
                <option value="{{ $country->name }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-span-1">
        <select class="select-single" style="width:100%" id="stateName">
            <option value="" selected>Select State</option>
        </select>
    </div>

</div>
<div class="p-4"></div>
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
            <thead class="bg-primary text-white ">
                <tr>

                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Name') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Course Country') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.ISO2') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.State') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Created By') }}</th>
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Created At') }}</th>
                    {{-- <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Is Page Ready') }}</th> --}}
                    <th class="px-4 py-3 whitespace-nowrap">{{ __('Course.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
</section>
@include('Course::Dynamic_pages.modal')
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sweetalert_js')
@include('Course::Dynamic_pages.extra_js')
@include('Course::Dynamic_pages.table_js')
@include('layouts.select2js')
@include('layouts.modal_js')


<script>
$(document).ready(function () {

    const $select = $('#country_id');
    let selectAllActive = false;

    // Checkbox inside dropdown
    function formatOption(option) {
        if (!option.id) return option.text;

        return $(`
            <span>
                <input type="checkbox" class="mr-2"/>
                ${option.text}
            </span>
        `);
    }

    // Init Select2
    $select.select2({
        placeholder: "Select Country...",
        closeOnSelect: false,
        templateResult: formatOption,

    });

    // Sync checkbox on select / unselect
    $select.on('select2:select select2:unselect', function () {
        syncDropdownCheckboxes();
    });

    // 🔥 THIS IS THE KEY FIX
    $select.on('select2:open', function () {
        syncDropdownCheckboxes();
    });

    function syncDropdownCheckboxes() {
        setTimeout(() => {
            $('.select2-results__option').each(function () {
                const selected = $(this).attr('aria-selected') === 'true';
                $(this).find('input[type="checkbox"]').prop('checked', selected);
            });
        }, 0);
    }

    // Select All checkbox
    $('#selectAllCountries').on('change', function () {

        const $select2 = $select.next('.select2');

        if (this.checked) {
            selectAllActive = true;

            const allValues = $select.find('option')
                .map(function () {
                    return this.value;
                }).get();

            $select.val(allValues).trigger('change');
            $select2.addClass('select-all-mode');

        } else {
            selectAllActive = false;

            // 🔥 HARD RESET
            $select.find('option').prop('selected', false);
            $select.val([]).trigger('change');
            $select2.removeClass('select-all-mode');
        }
    });

});
</script>
<script>
function startDownload() {

    // Show loader (optional)
    document.body.classList.add('loading');

    document.getElementById('downloadFrame').src =
        "{{ route('get.dynamic-course-pages') }}";

    setTimeout(function () {
        window.location.href =
            "{{ route('dynamic-course-pages-list') }}";
    }, 2000);
}
</script>
@endsection
