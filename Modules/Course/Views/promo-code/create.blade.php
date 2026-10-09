@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.select2css')
    <link rel="stylesheet" href="{{ asset('amybuild/css/daterange-picker.min.css') }}">
<style>
    /* hide selected tags */
.select-all-mode .select2-selection__choice {
    display: none !important;
}

/* hide placeholder text */
.select-all-mode .select2-selection__placeholder {
    display: none !important;
}

/* center text */
.select-all-mode .select2-selection--multiple {
    display: flex !important;
    align-items: center !important;
}

.select-all-mode .select2-selection__rendered {
    display: flex !important;
    align-items: center !important;
    padding-left: 12px;
}

/* show only All Selected */
.select-all-mode .select2-selection__rendered::before {
    content: "All Selected";
    font-size: 14px;
    color: #374151;
}

</style>

@endsection
@section('content')
    <div>
        @php echo breadcrums('common.promo-code-create',['common.home','common.promo-code-create'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    @section('extra_buttons')
        {{-- @php echo filter_button(); @endphp --}}
    @endsection
</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full bg-white border rounded-md p-4">
    @if ($data)
        <form action="{{ route('promo-code-update') }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('promo-code-store') }}" method="post" enctype="multipart/form-data">
    @endif
    @csrf
    @if ($data)
        <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
            data-maxlength="100" name="Course_id" required>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
       <div class="relative z-10 w-full">
            <label class="text-sm font-semibold text-black">
                <div class="flex justify-between items-center mb-1">
                    <span>{{ __('Course.Course Name') }}<span class="text-red-500">*</span></span>
                    <label class="flex items-center text-sm cursor-pointer">
                        Select All
                        <input id="selectAllCourses" type="checkbox"
                            class="w-4 h-4 ml-2 text-blue-600 border-gray-300 rounded">
                    </label>
                </div>
            </label>

            <!-- ✅ Hidden flag for backend -->
            <input type="hidden" name="select_all_courses" id="selectAllFlag" value="0">

            <select id="course_id" name="course_id[]" class="select-country w-full"  >
                {{-- Dynamic population --}}
            </select>
        </div>

       <div class="relative z-10 w-full">
            <label for="promo_code" class="text-sm font-semibold text-black">
                {{ __('Course.Code') }} <span class="text-red-500 font-bold">*</span>
            </label>
            <input type="text" id="promo_code" name="promocode" maxlength="15"
                placeholder="Enter promo code (max 15 chars)..." class="text-only form-input w-full pr-10"
                value="{{ old('promocode', $data?->promocode ?? '') }}" required />
            <div class="char-count text-xs text-gray-500 text-right mt-1" id="char-count">0/15</div>
        </div>
        <div class="relative z-10 w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('Course.date-range') }}
                <span class="text-red-500 font-bold">*</span></label>
            <input type="text" class="form-input daterange-input" id="daterange_1" name="daterange[]"
                value="01/01/2026 - 01/31/2026" required />
        </div>
        {{-- <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('Course.currency') }}
                <span class="text-red-500 font-bold">*</span></label>
            <input type="text" name="currency" placeholder="" class="input-box form-input" />

        </div> --}}

       <div class="relative z-10 w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('Course.discount-percentage') }}
                <span class="text-red-500 font-bold">*</span></label>
            <input type="number" name="discount_percentage" id="discount_percentage" class="form-input" min="0"
                max="99.99" step="0.01" placeholder="0.00"
                value="{{ old('discount_percentage', $data?->discount_percentage) }}">



        </div>

        <div class="relative z-10 w-full">
            <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Status') }}
                <span class="text-red-500 font-bold">*</span></label>
            <select class="select-single mt-1" style="width:100%" name="status" required>
                <option value=""></option>
                <option value="active" @if ($data?->status == 'active') selected @endif>Active
                </option>
                <option value="inactive" @if ($data?->status == 'inactive') selected @endif>Inactive
                </option>
            </select>
        </div>

              <div class="relative z-10 w-full">
            <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Publish Type') }}
                <span class="text-red-500 font-bold">*</span></label>
            <select class="select-single mt-1" style="width:100%" name="promo_type" required>
                <option value=""></option>
                <option value="public" @if ($data?->promo_type == 'public') selected @endif>Public
                </option>
                <option value="private" @if ($data?->promo_type == 'private') selected @endif>Private
                </option>
            </select>
        </div>

       <div class="relative z-10 w-full">
            <label for="promo_text" class="text-sm font-semibold text-black">
                {{ __('Course.text') }} <span class="text-red-500 font-bold">*</span>
            </label>
            <textarea id="promo_text" name="text" rows="4" placeholder="Enter promo description..."
                class="input-box form-input w-full" required>{{ old('text', $data?->text ?? '') }}</textarea>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <div class="flex gap-2">
            <button type="reset" class="btn-primary-sm flex items-center gap-1">
                <i class="ri-refresh-line"></i>
                {{ __('common.reset') }}
            </button>
            <button type="submit" class="btn-success-sm flex items-center gap-1">
                <i class="ri-check-line"></i>
                {{ __('common.submit') }}
            </button>
        </div>
    </div>
    </form>
</div>

@endsection
@section('scripts')
    @include('layouts.select2js')
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('layouts.sidebar_modal_js')
    <script src="{{ asset('amybuild/libs/jquery/daterange-moment.min.js') }}"></script>
    <script src="{{ asset('amybuild/libs/jquery/daterange-picker.min.js') }}"></script>
    <script>
        let rowIndex = 1;

        function initDateRange(input) {
            $(input).daterangepicker({
                opens: 'left',
                drops: 'down'
            });
        }
        $(function() {
            $('.daterange-input').each(function() {
                initDateRange(this);
            });
        });

        // Add Row
        document.getElementById('addRowBtn').addEventListener('click', function() {
            const tbody = document.querySelector('#data-table-list tbody');
            const firstRow = tbody.querySelector('tr');
            const newRow = firstRow.cloneNode(true);

            rowIndex++;

            newRow.querySelectorAll('input, select').forEach(el => {
                el.value = '';

                // Assign new unique ID for daterange
                if (el.classList.contains('daterange-input')) {
                    el.id = 'daterange_' + rowIndex;
                }
            });

            tbody.appendChild(newRow);

            initDateRange(newRow.querySelector('.daterange-input'));
        });

        $(document).on('click', '.ri-delete-bin-line', function() {
            const totalRows = $('#data-table-list tbody tr').length;
            if (totalRows > 1) {
                $(this).closest('tr').remove();
            }
        });
    </script>
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                drops: 'down'
            });
        });
    </script>
    {{-- <script>
    $(document).ready(function() {

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
        $select.on('select2:select select2:unselect', function() {
            syncDropdownCheckboxes();
        });

        // 🔥 THIS IS THE KEY FIX
        $select.on('select2:open', function() {
            syncDropdownCheckboxes();
        });

        function syncDropdownCheckboxes() {
            setTimeout(() => {
                $('.select2-results__option').each(function() {
                    const selected = $(this).attr('aria-selected') === 'true';
                    $(this).find('input[type="checkbox"]').prop('checked', selected);
                });
            }, 0);
        }

        // Select All checkbox
        $('#selectAllCountries').on('change', function() {

            const $select2 = $select.next('.select2');

            if (this.checked) {
                selectAllActive = true;

                const allValues = $select.find('option')
                    .map(function() {
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
</script> --}}

    {{-- <script>
        let selectAllActive = false;
        let coursesData = @json($courses ?? []); // Your courses data

        // ✅ Corrected Select All handler
        $('#selectAllCourses').on('change', function() {
            const $select = $('#course_id');
            const $select2 = $select.next('.select2');
            const $selectAllFlag = $('#selectAllFlag');

            if (this.checked) {
                // ✅ SELECT ALL: Clear selections + set flag
                selectAllActive = true;
                $select.val(null).trigger('change'); // Clear all selections
                $selectAllFlag.val('1'); // Send flag to backend
                $select.prop('disabled', true); // Disable manual selection
                $select2.addClass('select-all-mode');

                console.log('✅ Select All activated');
            } else {
                // ✅ DESELECT: Re-enable + reset flag
                selectAllActive = false;
                $selectAllFlag.val('0');
                $select.prop('disabled', false);
                $select.val(null).trigger('change');
                $select2.removeClass('select-all-mode');

                console.log('❌ Select All deactivated');
            }
        });

        // ✅ Form submit handler (optional confirmation)
        $('#yourFormId').on('submit', function() {
            if (selectAllActive) {
                if (!confirm('This will create a promo code for ALL courses. Continue?')) {
                    return false;
                }
            }
        });
    </script> --}}


    {{-- <script>
        $(document).ready(function() {
            const $select = $('#course_id');
            let selectAllActive = false;

            // 🔥 PHP courses data passed to JS
            const coursesData = @json($courses ?? []);
            const selectedCourses = @json(old('course_id', $data?->course_id ?? []));

            // Populate options from PHP data
            $select.empty();
            coursesData.forEach(function(course) {
                const isSelected = Array.isArray(selectedCourses) ?
                    selectedCourses.includes(course.id) :
                    selectedCourses == course.id;

                $select.append(`
                <option value="${course.id}" ${isSelected ? 'selected' : ''}>
                    ${course.course_title || course.name || course.title}
                </option>
            `);
            });

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
                placeholder: "Select Course...",
                closeOnSelect: false,
                templateResult: formatOption,
            });

            // Sync checkbox on select / unselect
            $select.on('select2:select select2:unselect', function() {
                syncDropdownCheckboxes();
            });

            $select.on('select2:open', function() {
                syncDropdownCheckboxes();
            });

            function syncDropdownCheckboxes() {
                setTimeout(() => {
                    $('.select2-results__option').each(function() {
                        const selected = $(this).attr('aria-selected') === 'true';
                        $(this).find('input[type="checkbox"]').prop('checked', selected);
                    });
                }, 0);
            }

            // Select All checkbox
            $('#selectAllCourses').on('change', function() {
                const $select2 = $select.next('.select2');
                if (this.checked) {
                    selectAllActive = true;
                    const allValues = coursesData.map(course => course.id);
                    $select.val(allValues).trigger('change');
                    $select2.addClass('select-all-mode');
                } else {
                    selectAllActive = false;
                    $select.val(null).trigger('change');
                    $select2.removeClass('select-all-mode');
                }
            });

            // Your existing onchange
            $select.on('change', function() {
                if (typeof filterPlans === 'function') {
                    filterPlans();
                }
            });
        });
    </script> --}}

<script>
    $(document).ready(function () {

        const $select = $('#course_id');
        const $selectAll = $('#selectAllCourses');
        const $flag = $('#selectAllFlag');

        const coursesData = @json($courses ?? []);
        const selectedCourseId = "{{ $data?->course_id ?? '' }}"; // 🔥 SINGLE ID
        const isSelectAll = "{{ $data?->select_all_courses ?? 0 }}" == "1";

        /* Populate dropdown */
        $select.append('<option></option>');
        coursesData.forEach(course => {
            $select.append(`
                <option value="${course.id}">
                    ${course.course_title || course.name || course.title}
                </option>
            `);
        });

        /* Init select2 (SINGLE SELECT) */
        $select.select2({
            placeholder: "Select Course",
            width: '100%',
            allowClear: true
        });

        /* 🔥 EDIT MODE */
        if (isSelectAll) {
            $selectAll.prop('checked', true);
            $flag.val('1');
            $select.prop('disabled', true);
            $select.next('.select2').addClass('select-all-mode');
        }
        else if (selectedCourseId) {
            // 🔥 ONLY ONE COURSE SHOW
            $select.val(selectedCourseId).trigger('change');
        }

        /* SELECT ALL HANDLER */
        $selectAll.on('change', function () {

            const $select2 = $select.next('.select2');

            if (this.checked) {
                $select.val(null).trigger('change');
                $select.prop('disabled', true);
                $select2.addClass('select-all-mode');
                $flag.val('1');
            } else {
                $select.prop('disabled', false);
                $select2.removeClass('select-all-mode');
                $select.val(null).trigger('change');
                $flag.val('0');
            }
        });

    });
</script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('promo_code');
            const counter = document.getElementById('char-count');

            function updateCounter() {
                const length = input.value.length;
                counter.textContent = length + '/15';
                counter.className = `char-count text-xs text-right mt-1 ${
            length > 15 ? 'text-red-500' : length > 10 ? 'text-orange-500' : 'text-gray-500'
        }`;
            }

            input.addEventListener('input', updateCounter);
            updateCounter(); // Initial count
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('discount_percentage');

            input.addEventListener('input', function() {
                let value = this.value;

                // If empty, allow
                if (value === '') return;

                // Convert to number
                let num = parseFloat(value);

                // Max 99.99
                if (num > 99.99) {
                    this.value = 99.99;
                    return;
                }

                // Limit to 2 decimal places while typing
                if (value.includes('.')) {
                    const parts = value.split('.');
                    parts[1] = parts[1].slice(0, 2);
                    this.value = parts.join('.');
                }
            });
        });
    </script>

<script>
    $(document).ready(function() {
        let today = new Date();

        // ✅ Current month ka 1st aur last date
        let currentMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
        let currentMonthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        $('#daterange_1').daterangepicker({
            startDate: currentMonthStart,
            endDate: currentMonthEnd,
            autoUpdateInput: false,
            minDate: currentMonthStart,
            locale: {
                format: 'MM/DD/YYYY',  // ✅ Month first!
            }
        }, function(start, end, label) {
            let formatted = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
            $('#daterange_1').val(formatted);
        });

        // ✅ MM/DD/YYYY format set
        let monthStart = currentMonthStart.toLocaleDateString('en-US', {
            month: '2-digit', day: '2-digit', year: 'numeric'
        });
        let monthEnd = currentMonthEnd.toLocaleDateString('en-US', {
            month: '2-digit', day: '2-digit', year: 'numeric'
        });

        let defaultValue = monthStart + ' - ' + monthEnd;
        $('#daterange_1').val(defaultValue);
    });
</script>





@endsection
