@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
    <style>
        .h-20 {
            height: 3rem !important;
        }

        /* hide selected tags */
        .select-all-mode .select2-selection__choice {
            display: none !important;
        }

        /* make selection area a flex container */
        .select-all-mode .select2-selection--multiple {
            display: flex !important;
            align-items: center !important;
            /* 🔥 vertical center */
        }

        /* rendered container full height */
        .select-all-mode .select2-selection__rendered {
            display: flex !important;
            align-items: center !important;
            width: 100%;
            padding-left: 12px;
            /* optional: same as input */
        }

        /* text */
        .select-all-mode .select2-selection__rendered::before {
            content: "All Selected";
            color: #374151;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <div>
        @php echo breadcrums('Course.Course Schedule Add', ['common.home', 'Course.Course Schedule List', 'Course.Course Schedule Add'], ['dashboard', 'bank-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form id="courseScheduleForm" action="{{ route('course-schedule-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('course-schedule-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="id" required>
        @endif



        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">

            {{-- <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Category') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="category_id" class="select-single mt-1" style="width:100%" name="course_category_id">
                    <option value="" disabled>Select Category..</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if (old('category_id', $data?->course_category_id) == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

           <div class="relative z-10 w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <select id="course_id" class="select-single mt-1" style="width:100%" name="course_id" required
                    onchange="filterPlans()">
                    <option value="" disabled>Select Course..</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @if (old('course_id', $data?->course_id) == $course->id) selected @endif>
                            {{ $course->course_title }}
                        </option>
                    @endforeach
                </select>
            </div>

           <div class="relative z-10 w-full">
                <label for="plan_id" class="text-sm font-semibold text-black">{{ __('Course.Plan Name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>

                @if (isset($data) && $data->plan_id)
                    <input type="hidden" id="saved_plan_id"
                        value="{{ is_array($data->plan_id) ? implode(',', $data->plan_id) : $data->plan_id }}">
                @endif

                <select id="plan_id" class="select-multi mt-1" style="width:100%" name="plan_id[]" required>
                    <!-- Empty - JS populates dynamically -->
                </select>
            </div>




        </div>

        <div class="my-2">
            <div class="flex justify-between items-center my-2">
                <label class="text-sm font-semibold text-black">It Includes</label>
                <button type="button" onclick="addInclude()" class="btn-primary-sm flex items-center">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="includes-container" class="space-y-2">
                <!-- Existing items will be added here -->
            </div>
        </div>

        <div>
            <label class="text-sm font-semibold text-black">Course Fee </label>
            <input type="text" name="course_fee" id="course_fee" class="form-input" readonly
                value="{{ old('course_fee', $data?->course_fees) }}">
        </div>

        <!-- Add this container for dynamic plan fee fields -->
        <div id="plan-fee-container" class="mt-2 space-y-1"></div>

        <div>
            <label class="text-sm font-semibold text-black">Discount Percentage</label>
            <input type="number" id ="disc_id" name="DiscountAmount" class="form-input" readonly
                value="{{ old('DiscountAmount', $data?->discount) }}">
        </div>
        <div id="disc-container" class="mt-2 space-y-1"></div>

        <!-- <hr class="my-6"> -->

        <!-- Batch Selection -->
        <div class="col-span-2 mt-2">
            <label class="text-sm font-semibold text-black mb-2 block">
                Batch <span class="text-red-500">*</span>
            </label>


            <div class="flex items-center bg-gray-100 rounded-lg p-1 w-fit">
                <button type="button" id="weekdayBtn"
                    class="px-4 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white transition"
                    onclick="selectBatch('weekday')">
                    All Week Batch
                </button>

                <button type="button" id="weekendBtn"
                    class="px-4 py-2 text-sm font-semibold rounded-md text-gray-700 transition"
                    onclick="selectBatch('weekend')">
                    Weekend Batch
                </button>
            </div>

            <!-- Hidden input to store value -->
            <input type="hidden" name="batch_type" id="batchType"
                value="{{ old('batch_type', $data?->batch_type ?? 'weekday') }}">
        </div>

        <div class="mt-2">

            <div class="grid grid-cols-12 gap-6 mt-6">

                <div class="col-span-12 md:col-span-6 flex">
                    <div class="flex-1 bg-white border rounded p-4 mt-4 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <button type = "button" onclick="prevMonth()"
                                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&lt;</button>
                            <h2 class="text-xl font-bold text-center" id="calendar-title"></h2>
                            <button type="button" onclick="nextMonth()"
                                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&gt;</button>
                        </div>


                        <input type="hidden" name="selected_dates" id="selected_dates" required>
                        <input type="hidden" name="selected_ranges" id="selected_ranges" required>

                        <div class="grid grid-cols-7 text-center font-semibold bg-gray-100 py-2 border">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>

                        <div id="calendar" class="grid grid-cols-7 border-l border-b"></div>
                    </div>
                </div>


                <div class="col-span-12 md:col-span-6 flex">
                    <div class="flex-1 bg-white border rounded p-4 mt-4 flex flex-col">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-black">Class Start Time</label>
                                <input type="time" class="form-input" name ="start_time"
                                    value="{{ old('start_time', $data?->class_start_time ?? '09:00') }}">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-black">Class End Date</label>
                                <input type="time" name = "end_time" class="form-input"
                                    value="{{ old('end_time', $data?->class_end_time ?? '17:00') }}">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-black">Class Language</label>
                                <input type="text" name = "language" class="form-input"
                                    value="{{ old('language', $data?->class_language ?? 'English') }}">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-black">Fee Currency</label>
                                <input type="text" name="feeCurrency" class="form-input" value="USD" readonly>
                            </div>


                            <div>
                                <label class="text-sm font-semibold text-black">Discount Offer For?
                                    (optional)</label>
                                <input type="text" name = "discount" class="form-input"
                                    value="{{ old('discount', $data?->discount_offer_for) }}">
                            </div>


                            {{-- <div>
                                <label class="text-sm font-semibold text-black">Discount last Date</label>
                                <input type="date" name="discountLastDate" class="form-input"
                                    value="{{ old('discountLastDate', $data?->discount_end_date) }}">
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-black">Enroll last Date & Time</label>
                                <input type="datetime-local" name="EnrollLastDate" class="form-input"
                                    value="{{ old('EnrollLastDate', $data?->enroll_end_date ? \Carbon\Carbon::parse($data->enroll_end_date)->format('Y-m-d\\TH:i') : '') }}">
                            </div> --}}


                            <div class="relative z-10 w-full">
                                <label for=""
                                    class=" text-sm font-semibold text-black">{{ __('Course.Status') }}
                                    <span class="text-red-500 font-bold">*</span></label>
                                <select class="select-single mt-1" style="width:100%" name="status" required>
                                    <option value=""></option>
                                    <option value="Public" @if ($data?->status == 'Public') selected @endif>Public
                                    </option>
                                    <option value="Private" @if ($data?->status == 'Private') selected @endif>Private
                                    </option>
                                </select>
                            </div>

                                        <div class="relative z-10 w-full">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Region Name') }}
                                       </label>
                                    <select id="region_id" class="select-single mt-1" name="region_id" >
                                        <option value="" selected disabled>Select Region</option>

                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}">
                                                {{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>


                            <div class="col-span-2  relative z-10 w-full">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-sm font-semibold text-black">
                                        Country Name <span class="text-red-500">*</span>
                                    </label>
                                    <label class="flex items-center text-sm cursor-pointer">
                                        Select All
                                        <input id="selectAllCountries" type="checkbox"
                                            class="w-4 h-4 ml-2 text-blue-600 border-gray-300 rounded">
                                    </label>
                                </div>

                                <select id="country_id" name="country_id[]" class="select-country w-full" multiple>
                                    @php
                                        $selectedCountries = [];

                                        // {{-- 🔥 FIXED: Check your controllers $data first --}}
                                        if (isset($data) && $data && $data->country_id) {
                                            $selectedCountries = [$data->country_id]; // 233 from your data
                                        }

                                        // {{-- Then check old input for validation errors --}}
                                        elseif (old('country_id')) {
                                            $selectedCountries = (array) old('country_id');
                                        }
                                    @endphp

                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ in_array($country->id, $selectedCountries) ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
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
</div>


@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.tab2_js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')


<script>
    // function selectBatch(type) {
    //     const weekdayBtn = document.getElementById('weekdayBtn');
    //     const weekendBtn = document.getElementById('weekendBtn');
    //     const batchInput = document.getElementById('batchType');

    //     if (type === 'weekday') {
    //         weekdayBtn.classList.add('bg-blue-600', 'text-white');
    //         weekdayBtn.classList.remove('text-gray-700');

    //         weekendBtn.classList.remove('bg-blue-600', 'text-white');
    //         weekendBtn.classList.add('text-gray-700');

    //         batchInput.value = 'weekday';
    //     } else {
    //         weekendBtn.classList.add('bg-blue-600', 'text-white');
    //         weekendBtn.classList.remove('text-gray-700');

    //         weekdayBtn.classList.remove('bg-blue-600', 'text-white');
    //         weekdayBtn.classList.add('text-gray-700');

    //         batchInput.value = 'weekend';
    //     }
    // }

    // Auto-select on page load (edit mode)
    document.addEventListener('DOMContentLoaded', function() {
        selectBatch(document.getElementById('batchType').value);
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
<script>
    const TOTAL_COUNTRIES = {{ $countries->count() }};
</script>

<script>
    const regionCountryMap = @json(
        $regions->mapWithKeys(function ($region) {
            return [
                $region->id => json_decode($region->Country ?? '[]', true)
            ];
        })
    );
</script>

<script>
$(document).ready(function () {

    $('#region_id').on('change', function () {
        const regionId = $(this).val();
        if (!regionId) return;

        const countryIds = regionCountryMap[regionId] || [];
        const $countrySelect = $('#country_id');
        const $selectAll = $('#selectAllCountries');
        const $select2 = $countrySelect.next('.select2');

        if (countryIds.length === TOTAL_COUNTRIES && TOTAL_COUNTRIES > 0) {
            // 🔥 AUTO SELECT ALL
            const allValues = $countrySelect.find('option').map(function () {
                return this.value;
            }).get();

            $countrySelect.val(allValues).trigger('change');

            $selectAll.prop('checked', true);
            $select2.addClass('select-all-mode');
        } else {
            // ✅ PARTIAL / NORMAL SELECTION
            $countrySelect.val(countryIds).trigger('change');

            $selectAll.prop('checked', false);
            $select2.removeClass('select-all-mode');
        }
    });

    // // 🔁 Auto-run on edit page load
    // if ($('#region_id').val()) {
    //     $('#region_id').trigger('change');
    // }

});
</script>




<script>
    $(document).ready(function() {
        const $select = $('#country_id');
        let selectAllActive = false;

        function formatOption(option) {
            if (!option.id) return option.text;
            return $(`
                <span>
                    <input type="checkbox" class="mr-2 form-checkbox"/>
                    ${option.text}
                </span>
            `);
        }

        // Destroy if exists
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            placeholder: "Select Country...",
            closeOnSelect: false,
            templateResult: formatOption,
            width: '100%'
        });

        // Refresh display
        $select.trigger('change');

        function syncDropdownCheckboxes() {
            setTimeout(() => {
                $('.select2-results__option').each(function() {
                    const selected = $(this).attr('aria-selected') === 'true';
                    const $checkbox = $(this).find('input[type="checkbox"]');
                    if ($checkbox.length) {
                        $checkbox.prop('checked', selected);
                    }
                });
            }, 50);
        }

        // Sync multiple times
        syncDropdownCheckboxes();
        setTimeout(syncDropdownCheckboxes, 100);
        setTimeout(syncDropdownCheckboxes, 300);

        $select.on('select2:select select2:unselect', syncDropdownCheckboxes);
        $select.on('select2:open', syncDropdownCheckboxes);

        $('#selectAllCountries').on('change', function() {
            const $select2 = $select.next('.select2');

            if (this.checked) {
                selectAllActive = true;
                const allValues = $select.find('option').map(function() {
                    return this.value;
                }).get();
                $select.val(allValues).trigger('change');
                $select2.addClass('select-all-mode');
            } else {
                selectAllActive = false;
                $select.val(null).trigger('change');
                $select2.removeClass('select-all-mode');
            }
            syncDropdownCheckboxes();
        });
    });
</script>


{{-- <script>
    function filterPlans() {
        const courseId = document.getElementById('course_id').value;
        const planSelect = document.getElementById('plan_id');

        // Clear all options
        planSelect.innerHTML = '';
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.disabled = true;
        defaultOption.textContent = 'Select Plans..';
        planSelect.appendChild(defaultOption);

        // Clear course fee and discount fields
        document.getElementById('course_fee').value = '';
        document.getElementById('disc_id').value = '';

        if (courseId) {
            fetch(`/course-schedule/plans/${courseId}`)
                .then(response => response.json())
                .then(plans => {
                    if (plans.length > 0) {
                        plans.forEach(plan => {
                            const option = document.createElement('option');
                            option.value = plan.id;
                            option.setAttribute('data-course-fee', plan.course_fee);
                            option.setAttribute('data-disc', plan.discount);
                            option.textContent = plan.name;
                            planSelect.appendChild(option);
                        });

                        // Trigger updateCourseFee to set initial values if there's a selected plan
                        if (planSelect.options.length > 1) {
                            updateCourseFee(planSelect);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching plans:', error);
                });
        }
    }

    function updateCourseFee(select) {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            const courseFee = selectedOption.getAttribute('data-course-fee');
            const disc = selectedOption.getAttribute('data-disc');
            document.getElementById('course_fee').value = courseFee || '';
            document.getElementById('disc_id').value = disc || '';
        } else {
            document.getElementById('course_fee').value = '';
            document.getElementById('disc_id').value = '';
        }
    }
</script> --}}

<script>
    function filterPlans() {
        const courseId = document.getElementById('course_id').value;
        const planSelect = document.getElementById('plan_id');

        planSelect.innerHTML = '';
        document.getElementById('course_fee').value = '';
        document.getElementById('disc_id').value = '';

        if (courseId) {
            fetch(`/course-schedule/plans/${courseId}`)
                .then(response => response.json())
                .then(plans => {
                    if (plans.length > 0) {
                        // Add plan options
                        plans.forEach(plan => {
                            const option = document.createElement('option');
                            option.value = plan.id;
                            option.setAttribute('data-course-fee', plan.course_fee);
                            option.setAttribute('data-disc', plan.discount);
                            option.textContent = plan.name;
                            planSelect.appendChild(option);
                        });

                        // DESTROY Select2 first
                        if ($.fn.select2) {
                            $('#plan_id').select2('destroy');
                        }

                        // Select saved plans OR Basic Plan
                        const savedPlanIdInput = document.getElementById('saved_plan_id');
                        let hasSavedPlan = false;

                        if (savedPlanIdInput && savedPlanIdInput.value) {
                            const savedPlanIds = savedPlanIdInput.value.split(',').map(id => id.trim()).filter(id =>
                                id);
                            savedPlanIds.forEach(savedId => {
                                const option = planSelect.querySelector(`option[value="${savedId}"]`);
                                if (option) {
                                    option.selected = true;
                                    hasSavedPlan = true;
                                }
                            });
                        }

                        // Default to Basic Plan if no saved plans
                        if (!hasSavedPlan) {
                            const basicOption = Array.from(planSelect.options).find(
                                opt => opt.textContent.trim() === 'Basic Plan'
                            );
                            if (basicOption) {
                                basicOption.selected = true;
                            }
                        }

                        // RE-INIT Select2 AFTER selection
                        if ($.fn.select2) {
                            $('#plan_id').select2({
                                placeholder: 'Select Plans',
                                allowClear: true
                            });
                        }

                        // FORCE update display + fields
                        setTimeout(() => {
                            $('#plan_id').trigger('change');
                            if (planSelect.value) {
                                updateCourseFee(planSelect);
                            }
                        }, 100); // Small delay for Select2 render

                    } else {
                        $('#plan_id').select2({
                            placeholder: 'No plans available'
                        });
                    }
                });
        }
    }





    function updateCourseFee(selectElement) {
        const feeContainer = document.getElementById('plan-fee-container');
        const discContainer = document.getElementById('disc-container');

        feeContainer.innerHTML = '';
        discContainer.innerHTML = '';

        // Fix for Select2: Get selected values properly
        let selectedValues = [];
        if ($.fn.select2 && $(selectElement).hasClass('select2-hidden-accessible')) {
            // Select2: get data
            const select2Data = $(selectElement).select2('data');
            selectedValues = select2Data.map(item => item.id);
        } else {
            // Native select
            selectedValues = Array.from(selectElement.selectedOptions).map(opt => opt.value);
        }

        if (selectedValues.length > 0) {
            // Get options by value
            const selectedOptions = selectedValues.map(val =>
                selectElement.querySelector(`option[value="${val}"]`)
            ).filter(opt => opt);

            // Basic Plan priority for main fields
            const basicOption = selectedOptions.find(opt => opt.textContent.trim() === 'Basic Plan') || selectedOptions[
                0];
            const mainFee = parseFloat(basicOption.getAttribute('data-course-fee') || 0);
            const mainDisc = parseFloat(basicOption.getAttribute('data-disc') || 0);

            document.getElementById('course_fee').value = mainFee.toFixed(2);
            document.getElementById('disc_id').value = mainDisc.toFixed(2);

            // Create fields for ALL selected plans
            selectedOptions.forEach(option => {
                const planName = option.textContent;
                const planFee = option.getAttribute('data-course-fee');
                const planDisc = option.getAttribute('data-disc');
                const planId = option.value;

                // if (planFee) {
                //     // Fee row + HIDDEN input for form
                //     const feeDiv = document.createElement('div');
                //     feeDiv.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border mb-1';
                //     feeDiv.innerHTML = `
                //         <span class="text-xs font-medium text-gray-700">${planName}</span>
                //         <span class="text-xs text-gray-900">${planFee}</span>
                //         <input type="hidden" name="plan_fees[${planId}]" value="${planFee}">
                //     `;
                //     feeContainer.appendChild(feeDiv);
                // }

                // if (planDisc) {
                //     // Discount row + HIDDEN input
                //     const discDiv = document.createElement('div');
                //     discDiv.className = 'flex items-center justify-between p-2 bg-blue-50 rounded border mb-1';
                //     // discDiv.innerHTML = `
                //     //     <span class="text-xs font-medium text-blue-700">${planName}</span>
                //     //     <span class="text-xs text-blue-900">${planDisc}%</span>
                //     //     <input type="hidden" name="plan_discounts[${planId}]" value="${planDisc}">
                //     // `;
                //     discContainer.appendChild(discDiv);
                // }
            });
        } else {
            document.getElementById('course_fee').value = '';
            document.getElementById('disc_id').value = '';
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const courseId = document.getElementById('course_id')?.value;
        if (courseId) {
            filterPlans();
        }
    });

    document.getElementById('course_id')?.addEventListener('change', filterPlans);

    // Select2 change event
    $(document).on('change', '#plan_id', function() {
        updateCourseFee(this);
    });
</script>

<script>
    function validateFileInput(inputId, errorId, allowedTypes, maxSizeMB) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        const maxSize = maxSizeMB * 1024 * 1024;

        input.addEventListener('change', function() {
            error.textContent = '';
            error.classList.add('hidden');

            const file = input.files[0];
            if (!file) return;

            if (!allowedTypes.includes(file.type)) {
                error.textContent =
                    `Invalid file type. Allowed: ${allowedTypes.map(t => t.split('/')[1]).join(', ').toUpperCase()}`;
                error.classList.remove('hidden');
                input.value = '';
                return;
            }

            if (file.size > maxSize) {
                error.textContent = `File "${file.name}" exceeds the ${maxSizeMB}MB limit.`;
                error.classList.remove('hidden');
                input.value = '';
                return;
            }
        });
    }

    validateFileInput('feature_image', 'feature-image-error', ['image/jpeg', 'image/png'], 2);
    validateFileInput('brochure_file', 'brochure-error', ['application/pdf'], 2);

    function addInclude() {
        const container = document.getElementById('includes-container');
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'space-x-2', 'border', 'p-3', 'bg-white', 'rounded-lg', 'shadow');
        div.innerHTML = `
            <input type="text" name="includes[]" placeholder="Enter item"
            class="form-input" />
            <button type="button" onclick="removeItem(this)"
            class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600 ">
            <i class="ri-delete-bin-line "></i>  Delete
            </button>
        `;
        container.appendChild(div);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }
</script>


{{-- <script>
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth() + 1;

    const calendarEl = document.getElementById("calendar");
    const titleEl = document.getElementById("calendar-title");

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const monthNames = [
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
    ];

    const rangeColors = [
        { start: 'bg-blue-600', mid: 'bg-blue-200' },
        { start: 'bg-green-600', mid: 'bg-green-200' },
        { start: 'bg-purple-600', mid: 'bg-purple-200' },
        { start: 'bg-gray-600', mid: 'bg-gray-200' },
        { start: 'bg-pink-600', mid: 'bg-pink-200' }
    ];

    let ranges = [];
    let ranges_for_back = [];
    let tempStart = null;

    /* ================= RENDER ================= */
    function renderCalendar() {
        titleEl.textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

        let html = "";

        for (let i = 0; i < firstDay; i++) {
            html += `<div class="h-20 border-r border-t bg-gray-50"></div>`;
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const d = new Date(currentYear, currentMonth - 1, day);

            let css = "h-20 border-r border-t flex items-center justify-center ";
            let label = "font-semibold text-sm ";

            let lastIndex = -1;
            ranges.forEach((r, i) => {
                const s = new Date(r.start.year, r.start.month - 1, r.start.day);
                const e = new Date(r.end.year, r.end.month - 1, r.end.day);
                if (d >= s && d <= e) lastIndex = i;
            });

            if (lastIndex !== -1) {
                const r = ranges[lastIndex];
                const c = rangeColors[lastIndex % rangeColors.length];

                const isStart =
                    r.start.year === currentYear &&
                    r.start.month === currentMonth &&
                    r.start.day === day;

                const isEnd =
                    r.end.year === currentYear &&
                    r.end.month === currentMonth &&
                    r.end.day === day;

                css += isStart || isEnd
                    ? `${c.start} text-white rounded-md`
                    : `${c.mid}`;
            } else if (d < today) {
                css += "bg-gray-100 text-gray-400 cursor-not-allowed";
            } else {
                css += "hover:bg-gray-100 cursor-pointer";
            }

            html += `
                <div onclick="selectDate(${day})" class="${css}">
                    <span class="${label}">${day}</span>
                </div>
            `;
        }

        calendarEl.innerHTML = html;
    }

    /* ================= CLICK ================= */
    window.selectDate = function (day) {
        const clicked = new Date(currentYear, currentMonth - 1, day);
        if (clicked < today) return;

        /*  DELETE ONLY WHEN NOT SELECTING */
        if (!tempStart) {
            let lastMatch = -1;
            ranges.forEach((r, i) => {
                const s = new Date(r.start.year, r.start.month - 1, r.start.day);
                const e = new Date(r.end.year, r.end.month - 1, r.end.day);
                if (clicked >= s && clicked <= e) lastMatch = i;
            });

            if (lastMatch !== -1) {
                ranges.splice(lastMatch, 1);
                ranges_for_back.splice(lastMatch, 1);
                renderCalendar();
                return;
            }
        }

        const dateObj = { year: currentYear, month: currentMonth, day };

        if (!tempStart) {
            tempStart = dateObj;
        } else {
            const s = new Date(tempStart.year, tempStart.month - 1, tempStart.day);
            const e = clicked;

            const start = s <= e ? tempStart : dateObj;
            const end   = s <= e ? dateObj : tempStart;

            ranges.push({ start, end });
            ranges_for_back.push({
                starts: `${start.day}/${start.month}/${start.year}`,
                ends: `${end.day}/${end.month}/${end.year}`
            });

            tempStart = null;
        }

        renderCalendar();
    };

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 12) { currentMonth = 1; currentYear++; }
        renderCalendar();
    }

    function prevMonth() {
        currentMonth--;
        if (currentMonth < 1) { currentMonth = 12; currentYear--; }
        renderCalendar();
    }

    renderCalendar();

</script> --}}
<script>
    /* ================= GLOBAL STATE ================= */
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth() + 1;
    let batchType = 'weekday';

    const calendarEl = document.getElementById("calendar");
    const titleEl = document.getElementById("calendar-title");

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const rangeColors = [{
            start: 'bg-blue-600',
            mid: 'bg-blue-200'
        },
        {
            start: 'bg-green-600',
            mid: 'bg-green-200'
        },
        {
            start: 'bg-purple-600',
            mid: 'bg-purple-200'
        },
        {
            start: 'bg-gray-600',
            mid: 'bg-gray-200'
        },
        {
            start: 'bg-pink-600',
            mid: 'bg-pink-200'
        }
    ];

    /* ================= DATA ================= */
    let ranges = [];
    let ranges_for_back = [];
    let tempStart = null;
    let weekendDates = [];

    /* ================= LOAD EXISTING DATES ================= */
    const classStartDate = "{{ $data?->class_start_date }}";
    const classEndDate = "{{ $data?->class_end_date }}";

    if (classStartDate && classEndDate) {
        const [sy, sm, sd] = classStartDate.split('-').map(Number);
        const [ey, em, ed] = classEndDate.split('-').map(Number);

        ranges.push({
            start: {
                year: sy,
                month: sm,
                day: sd
            },
            end: {
                year: ey,
                month: em,
                day: ed
            }
        });

        ranges_for_back.push({
            starts: `${sd}/${sm}/${sy}`,
            ends: `${ed}/${em}/${ey}`
        });
    }

    /* ================= BATCH TOGGLE ================= */
    function selectBatch(type) {
        // alert(type);
        batchType = type;
        tempStart = null;

        const weekdayBtn = document.getElementById('weekdayBtn');
        const weekendBtn = document.getElementById('weekendBtn');
        const batchInput = document.getElementById('batchType');

        weekdayBtn.className =
            "px-4 py-2 text-sm font-semibold rounded-md text-gray-700 transition";
        weekendBtn.className =
            "px-4 py-2 text-sm font-semibold rounded-md text-gray-700 transition";

        if (type === 'weekday') {
            weekdayBtn.classList.add('bg-blue-600', 'text-white');
        } else {
            weekendBtn.classList.add('bg-blue-600', 'text-white');
        }
        batchInput.value = batchType;

        renderCalendar();
    }

    /* ================= RENDER CALENDAR ================= */
    function renderCalendar() {
        titleEl.textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

        let html = "";

        for (let i = 0; i < firstDay; i++) {
            html += `<div class="h-20 border-r border-t bg-gray-50"></div>`;
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const d = new Date(currentYear, currentMonth - 1, day);
            const isWeekend = d.getDay() === 0 || d.getDay() === 6;

            let css = "h-20 border-r border-t flex items-center justify-center ";
            let label = "font-semibold text-sm ";

            /* ===== WEEKEND MODE ===== */
            if (batchType === 'weekend') {
                const selected = weekendDates.find(w =>
                    w.year === currentYear &&
                    w.month === currentMonth &&
                    w.day === day
                );

                if (selected) {
                    css += "bg-blue-500 text-white rounded-md";
                } else if (!isWeekend || d < today) {
                    css += "bg-gray-100 text-gray-400 cursor-not-allowed pointer-events-none";
                } else {
                    css += "hover:bg-blue-100 cursor-pointer";
                }
            }

            /* ===== WEEKDAY MODE ===== */
            else {
                let lastIndex = -1;
                ranges.forEach((r, i) => {
                    const s = new Date(r.start.year, r.start.month - 1, r.start.day);
                    const e = new Date(r.end.year, r.end.month - 1, r.end.day);
                    if (d >= s && d <= e) lastIndex = i;
                });

                if (lastIndex !== -1) {
                    const r = ranges[lastIndex];
                    const c = rangeColors[lastIndex % rangeColors.length];

                    const isStart =
                        r.start.year === currentYear &&
                        r.start.month === currentMonth &&
                        r.start.day === day;

                    const isEnd =
                        r.end.year === currentYear &&
                        r.end.month === currentMonth &&
                        r.end.day === day;

                    css += isStart || isEnd ?
                        `${c.start} text-white rounded-md` :
                        `${c.mid}`;
                } else if (d < today) {
                    css += "bg-gray-100 text-gray-400 cursor-not-allowed pointer-events-none";
                } else {
                    css += "hover:bg-gray-100 cursor-pointer";
                }
            }

            html += `
            <div onclick="selectDate(${day})" class="${css}">
                <span class="${label}">${day}</span>
            </div>
        `;
        }

        calendarEl.innerHTML = html;
        updatePayload();
    }

    /* ================= CLICK HANDLER ================= */
    window.selectDate = function(day) {
        const clicked = new Date(currentYear, currentMonth - 1, day);
        if (clicked < today) return;

        /* 🔒 BLOCK WEEKDAYS IN WEEKEND MODE */
        if (batchType === 'weekend' && clicked.getDay() !== 0 && clicked.getDay() !== 6) {
            return;
        }

        /* ===== WEEKEND ===== */
        if (batchType === 'weekend') {
            const index = weekendDates.findIndex(w =>
                w.year === currentYear &&
                w.month === currentMonth &&
                w.day === day
            );

            if (index !== -1) {
                weekendDates.splice(index, 1);
            } else {
                weekendDates.push({
                    year: currentYear,
                    month: currentMonth,
                    day
                });
            }

            renderCalendar();
            return;
        }

        /* ===== WEEKDAY RANGE ===== */
        if (!tempStart) {
            let lastMatch = -1;
            ranges.forEach((r, i) => {
                const s = new Date(r.start.year, r.start.month - 1, r.start.day);
                const e = new Date(r.end.year, r.end.month - 1, r.end.day);
                if (clicked >= s && clicked <= e) lastMatch = i;
            });

            if (lastMatch !== -1) {
                ranges.splice(lastMatch, 1);
                ranges_for_back.splice(lastMatch, 1);
                renderCalendar();
                return;
            }
        }

        const dateObj = {
            year: currentYear,
            month: currentMonth,
            day
        };

        if (!tempStart) {
            tempStart = dateObj;
        } else {
            const s = new Date(tempStart.year, tempStart.month - 1, tempStart.day);
            const e = clicked;

            const start = s <= e ? tempStart : dateObj;
            const end = s <= e ? dateObj : tempStart;

            ranges.push({
                start,
                end
            });
            ranges_for_back.push({
                starts: `${start.day}/${start.month}/${start.year}`,
                ends: `${end.day}/${end.month}/${end.year}`
            });

            tempStart = null;
        }

        renderCalendar();
    };

    /* ================= PAYLOAD ================= */
    function updatePayload() {
        const input = document.getElementById('selected_ranges');
        if (input) {
            input.value = JSON.stringify({
                weekday_ranges: ranges_for_back,
                weekend_dates: weekendDates
            });
        }
    }

    /* ================= MONTH NAV ================= */
    function nextMonth() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        renderCalendar();
    }

    function prevMonth() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        renderCalendar();
    }

    /* ================= INIT ================= */
    document.addEventListener('DOMContentLoaded', renderCalendar);
</script>






<script>
    $(document).ready(function() {
        $('#selectAllCountries').change(function() {
            if ($(this).is(':checked')) {
                $('#country_id option:not(:disabled)').prop('selected', true);
            } else {
                $('#country_id option').prop('selected', false);
            }
            $('#country_id').trigger('change');
        });

        $('#country_id').trigger('change');
    });
</script>

{{-- <script>
function updateCourseFee(select) {
    const selectedOption = select.options[select.selectedIndex];
    const courseFee = selectedOption.getAttribute('data-course-fee');
    const disc = selectedOption.getAttribute('data-disc');
    document.getElementById('course_fee').value = courseFee || '';
    document.getElementById('disc_id').value = disc || '';
}
</script> --}}




<script>
    const existingIncludes = @json(json_decode($data?->course_includes ?? '[]', true));

    function addInclude(value = '') {
        const container = document.getElementById('includes-container');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 rounded-md';
        div.innerHTML = `
            <input type="text" name="includes[]" placeholder="Enter item" class="form-input" value="${value}" />
            <button type="button" onclick="removeItem(this)"
                class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600">
                <i class="ri-delete-bin-line"></i> Delete
            </button>
        `;
        container.appendChild(div);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (existingIncludes.length > 0) {
            existingIncludes.forEach(item => {
                addInclude(item);
            });
        } else {
            addInclude();
        }
    });
</script>

<script>
$(document).ready(function () {

    const $country = $('#country_id');

    // 🔥 RESET button click pe COUNTRY hard reset
    $('button[type="reset"]').on('click', function () {

        setTimeout(() => {

            // 1️⃣ Clear value
            $country.val(null);

            // 2️⃣ DESTROY select2 (IMPORTANT)
            if ($country.hasClass('select2-hidden-accessible')) {
                $country.select2('destroy');
            }

            // 3️⃣ Re-init select2 (same config)
            $country.select2({
                placeholder: "Select Country...",
                closeOnSelect: false,
                templateResult: function (option) {
                    if (!option.id) return option.text;
                    return $(
                        `<span>
                            <input type="checkbox" class="mr-2 form-checkbox"/>
                            ${option.text}
                        </span>`
                    );
                },
                width: '100%'
            });

            // 4️⃣ Select All checkbox reset
            $('#selectAllCountries').prop('checked', false);
            $country.next('.select2').removeClass('select-all-mode');

        }, 0);

    });

});
</script>

@endsection
