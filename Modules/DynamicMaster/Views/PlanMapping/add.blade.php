@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')

    <div>


        @php echo breadcrums('common.plan-add',['common.home','common.plan','common.plan-add'],['dashboard','plan-index']) @endphp
@section('back_button')
    @php echo back_button('plan-mapping-index') @endphp
@endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        {{-- @if ($data) --}}
        {{-- <form action="{{ route('course-location-update') }}" method="post">
                <input type="hidden" name="location_id" value="{{ encrypt($data?->id) }}"> --}}
        {{-- @else --}}
        @php
        @endphp
        <form action="{{ route('plan-mapping-store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 w-full my-2">

                {{-- <div class="w-full">
                        <label class="text-sm font-semibold text-black">Name
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="limited-input form-input mt-1" name="name" data-maxlength="100"
                            required>
                    </div> --}}

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="course_id" id = "course_id" required>
                        <option value="">Select Features...</option>
                        @foreach ($course as $item)
                            <option value="{{ $item->id }}">{{ $item->course_short_name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('common.plan-name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="plan_id" id="plan_id" required>
                        <option value="">Select Plan...</option>
                        @foreach ($plan as $item)
                            <option value="{{ $item->id }}" data-discount="{{ $item->baseDiscount }}"
                                data-recom="{{ $item->recommended }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
                {{-- <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('common.Country') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="country_id" required>
                        <option value="">Select Features...</option>
                        @foreach ($country as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="w-full">
    <label class="text-sm font-semibold text-black">{{ __('common.course-fee') }}
        <span class="text-red-500 font-bold">*</span>
    </label>
    <input
        type="text"
        class="coord-input form-input mt-1"
        name="course_fee"
        id="course_fee"
        placeholder="0.00"
        maxlength="10"
        required
        data-max="9999.99"
    >
    {{-- <small class="text-xs text-gray-500">Max: 99999.99</small> --}}
</div>




                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('Course.Discount %') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="coord-input form-input mt-1" name="discount" maxlength="5" required>
                </div>
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('Course.tax') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="coord-input form-input mt-1" name="tax" maxlength="5" required>
                </div>

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Recommended ('yes' or 'no')</label>

                    <input type="text" class="form-input mt-1" name="recommended" required value="no" readonly>
                    {{-- <select class="select-single mt-1" style="width:100%" name="recommended" required>
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>

                    </select> --}}
                </div>

            </div>
           {{-- <div class="w-1/2">


               <label class="text-sm font-semibold text-black block mb-2">
                    Included Features <span class="text-red-500 font-bold">*</span>
                    <button type="button" onclick="openEditFeatures(document.getElementById('plan_id').value, document.getElementById('course_id').value); openNewModal('edit-features')">
                        <i class="ri-edit-2-line"></i>
                    </button>
                </label>



                <div class="space-y-2" id="included-features-container">
                    <!-- Checkboxes will be added here dynamically -->
                </div>
            </div> --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Included Features Column -->
                <div class="w-full">
                    <label class="text-sm font-semibold text-black block mb-2">
                        Included Features <span class="text-red-500 font-bold">*</span>
                        <button type="button" onclick="openEditFeatures(document.getElementById('plan_id').value, document.getElementById('course_id').value); openNewModal('edit-features')"
                                class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="ri-edit-2-line"></i>
                        </button>
                    </label>
                    <div class="space-y-2 p-3 border rounded-md bg-green-50 min-h-[200px]" id="included-features-container">
                        <!-- Dynamic included features -->
                    </div>
                </div>

                <!-- Excluded Features Column -->
                <div class="w-full">
                    <label class="text-sm font-semibold text-black block mb-2">
                        Excluded Features <span class="text-red-500 font-bold">*</span>
                        <button type="button" onclick="openEditFeatures(document.getElementById('plan_id').value, document.getElementById('course_id').value); openNewModal('edit-features')"
                                class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="ri-edit-2-line"></i>
                        </button>
                    </label>
                    <div class="space-y-2 p-3 border rounded-md bg-red-50 min-h-[200px]" id="excluded-features-container">
                        <!-- Dynamic excluded features -->
                    </div>
                </div>
            </div>

<!-- Hidden inputs (place anywhere on page) -->
{{-- <input type="hidden" id="plan_id" value="{{ $plan->id ?? '' }}">
<input type="hidden" id="course_id" value="{{ $course->id ?? '' }}"> --}}



            <div class="flex justify-end mt-4">
                <div class="flex gap-2">
                    <button type="reset" class="btn-primary-sm flex items-center gap-1">
                        <i class="ri-refresh-line"></i> {{ __('common.reset') }}
                    </button>
                    <button type="submit" class="btn-success-sm flex items-center gap-1">
                        <i class="ri-check-line"></i> {{ __('common.submit') }}
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
@include('dynamicmaster::PlanMapping.modal')
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@include('layouts.modal_js')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const features = @json($features);
        const plans = @json($plan);
        // const COURSE_ID = @json($course);
        // console.log(COURSE_ID);

        $('select[name="plan_id"]').on('change', function() {
            const selected = this.options[this.selectedIndex];
            const discount = selected.getAttribute('data-discount') || '';
            const recomm = selected.getAttribute('data-recom') || '';

            document.querySelector('input[name="discount"]').value = discount;
            document.querySelector('input[name="recommended"]').value = recomm;

            // const planId = parseInt($('select[name="plan_id"]').val());
            const courseId = parseInt($('select[name="course_id"]').val());

            const planId = parseInt(this.value);
            if (!planId) {
                clearFeatureContainers();
                return;
            }

            loadPlanFeatures(planId , courseId);
        });

        // Initial load
        const initialPlanId = $('select[name="plan_id"]').val();
        if (initialPlanId) {
            loadPlanFeatures(parseInt(initialPlanId),courseId);
        }
    });

    function loadPlanFeatures(planId, courseId) {
        fetch(`/plan-mapping/plan/${planId}/included-features?course_id=${courseId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Loaded features:', data);

                // Clear both containers
                clearFeatureContainers();

                // ✅ INCLUDED
                const includedContainer = document.getElementById('included-features-container');
                (data.included || []).forEach(featureName => {
                    const div = createFeatureDisplay(featureName, true);
                    includedContainer.appendChild(div);
                });

                // ✅ EXCLUDED
                const excludedContainer = document.getElementById('excluded-features-container');
                (data.excluded || []).forEach(featureName => {
                    const div = createFeatureDisplay(featureName, false);
                    excludedContainer.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Error fetching features:', error);
                showErrorState();
            });
    }


    function createFeatureDisplay(featureName, isIncluded) {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 p-2 bg-white border rounded-md shadow-sm hover:shadow-md transition-shadow';

        const iconClass = isIncluded ? 'bg-green-500' : 'bg-red-500';
        const icon = isIncluded ? 'ri-check-line' : 'ri-close-line';
        const hiddenName = isIncluded ? 'included_features[]' : 'excluded_features[]';

        div.innerHTML = `
            <div class="w-5 h-5 ${iconClass} rounded-full flex items-center justify-center flex-shrink-0">
                <i class="${icon} text-white text-xs"></i>
            </div>
            <span class="flex-1 text-sm font-medium text-gray-900 truncate">${featureName}</span>
            <input type="hidden" name="${hiddenName}" value="${featureName}">
        `;
        return div;
    }

    function clearFeatureContainers() {
        const includedContainer = document.getElementById('included-features-container');
        const excludedContainer = document.getElementById('excluded-features-container');

        if (includedContainer) includedContainer.innerHTML = '';
        if (excludedContainer) excludedContainer.innerHTML = '';
    }

    function showErrorState() {
        const includedContainer = document.getElementById('included-features-container');
        const excludedContainer = document.getElementById('excluded-features-container');

        if (includedContainer) {
            includedContainer.innerHTML = '<div class="text-red-500 text-sm p-3">Error loading included features</div>';
        }
        if (excludedContainer) {
            excludedContainer.innerHTML = '<div class="text-red-500 text-sm p-3">Error loading excluded features</div>';
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseFeeInput = document.getElementById('course_fee');

    courseFeeInput.addEventListener('input', function(e) {
        let value = e.target.value;

        // Remove any non-numeric characters except decimal point
        value = value.replace(/[^0-9.]/g, '');

        // Allow only one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        // Limit decimal places to 2
        if (parts.length === 2) {
            parts[1] = parts[1].substring(0, 2);
            value = parts.join('.');
        }

        // Limit integer part to 5 digits
        const integerPart = parts[0];
        if (integerPart.length > 4) {
            value = integerPart.substring(0, 4) + (parts.length > 1 ? '.' + parts[1] : '');
        }

        // Limit total value to 99999.99
        // const numValue = parseFloat(value) || 0;
        // if (numValue > 9999.99) {
        //     value = '9999.99';
        // }

        e.target.value = value;
    });

    // Prevent paste of invalid data
    courseFeeInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const cleanPaste = paste.replace(/[^0-9.]/g, '');
        document.execCommand('insertText', false, cleanPaste);
    });
});
</script>

@endsection




