@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection

@php
    $included = json_decode($mapping['included_features'], true);
    // $included = json_decode($plan['included_features'] ?? '[]', true);
@endphp

@section('content')
    <div>

@section('back_button')
    @php echo back_button('plan-mapping-index') @endphp
@endsection
    <h3 class="text-lg p-4 my-2 w-full border bg-white rounded-md "> {{ $flag }} Course-Plan Map</h3>
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        {{-- @if ($data) --}}
        {{-- <form action="{{ route('course-location-update') }}" method="post">
                <input  {{ $readonly }} type="hidden" name="location_id" value="{{ encrypt($data?->id) }}"> --}}
        {{-- @else --}}
        @php
            $readonly = $flag == 'View' ? 'readonly' : '';
        @endphp
        <form action="{{ route('plan-mapping.update', encrypt($mapping->id)) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 w-full my-2">

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <select id="course_id" class="select-single mt-1" style="width:100%" name="course_id" required
                        {{ $flag == 'View' ? 'disabled' : '' }}>
                        <option value="">Select Features...</option>
                        @foreach ($course as $item)
                            <option value="{{ $item->id }}"
                                {{ $mapping->course_id == $item->id ? 'selected' : '' }}>
                                {{ $item->course_short_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('common.plan-name') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <select id="plan_id" class="select-single mt-1" style="width:100%" name="plan_id" required
                        {{ $flag == 'View' ? 'disabled' : '' }} {{ $flag == 'View' ? 'disabled' : '' }}>
                        <option value="">Select Features...</option>
                        @foreach ($plan as $item)
                            <option value="{{ $item->id }}"
                                {{ $mapping->plan_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="w-full">
                        <label class="text-sm font-semibold text-black">{{ __('common.Country') }}
                            <span class="text-red-500 font-bold">*</span>
                        </label>
                        <select class="select-single mt-1" style="width:100%" name="country_id" required
                            {{ $flag == 'View' ? 'disabled' : '' }}>
                            <option value="">Select...</option>
                            @foreach ($country as $item)
                                <option value="{{ $item->id }}"
                                    {{ $mapping->country_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('common.course-fee') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text" class="coord-input form-input mt-1" name="course_fee" id="course_fee"
                        placeholder="0.00" maxlength="10" required data-max="9999.99" {{ $readonly }}
                        value="{{ $mapping->course_fee }}" required>

                    {{-- <small class="text-xs text-gray-500">Max: 99999.99</small> --}}
                </div>

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('Course.Discount %') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text" class="coord-input form-input mt-1" name="discount" maxlength="5"
                        {{ $readonly }} value="{{ $mapping->discount }}" required>
                </div>

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">{{ __('Course.tax') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text" class="coord-input form-input mt-1" name="tax" maxlength="5"
                        {{ $readonly }} value="{{ $mapping->tax }}" required>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Included Features Column -->
                <div class="w-full">
                    <label class="text-sm font-semibold text-black block mb-2">
                        Included Features <span class="text-red-500 font-bold">*</span>
                        @if (Route::currentRouteName() === 'plan-mapping.edit')
                            <button type="button"
                                onclick="openEditFeatures(
            document.getElementById('plan_id').value,
            document.getElementById('course_id').value
        ); openNewModal('edit-features')"
                                class="ml-2 text-blue-600 hover:text-blue-800">
                                <i class="ri-edit-2-line"></i>
                            </button>
                        @endif

                    </label>
                    <div class="space-y-2 p-3 border rounded-md bg-green-50 min-h-[200px]"
                        id="included-features-container">
                        <!-- Dynamic included features -->
                    </div>
                </div>

                <!-- Excluded Features Column -->
                <div class="w-full">
                    <label class="text-sm font-semibold text-black block mb-2">
                        Excluded Features <span class="text-red-500 font-bold">*</span>
                        @if (Route::currentRouteName() === 'plan-mapping.edit')
                            <button type="button"
                                onclick="openEditFeatures(
            document.getElementById('plan_id').value,
            document.getElementById('course_id').value
        ); openNewModal('edit-features')"
                                class="ml-2 text-blue-600 hover:text-blue-800">
                                <i class="ri-edit-2-line"></i>
                            </button>
                        @endif
                    </label>
                    <div class="space-y-2 p-3 border rounded-md bg-red-50 min-h-[200px]"
                        id="excluded-features-container">
                        <!-- Dynamic excluded features -->
                    </div>
                </div>
            </div>


            <div class="flex justify-end mt-4">
                <div class="flex gap-2">
                    <a href="{{ route('plan-mapping-index') }}" class="btn-primary-sm flex items-center gap-1">
                        <i class="ri-arrow-left-line"></i> Back
                    </a>
                    @if ($flag == 'Edit')
                        <button type="submit" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i> {{ __('common.update') }}
                        </button>
                    @endif
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

        function triggerFeatureLoad() {
            const planId = parseInt($('select[name="plan_id"]').val());
            const courseId = parseInt($('select[name="course_id"]').val());

            if (!planId || !courseId) {
                clearFeatureContainers();
                return;
            }

            loadPlanFeatures(planId, courseId);
        }

        // When PLAN changes
        $('select[name="plan_id"]').on('change', function() {
            triggerFeatureLoad();
        });

        // When COURSE changes
        $('select[name="course_id"]').on('change', function() {
            triggerFeatureLoad();
        });

        // ✅ Initial load (Edit / View)
        triggerFeatureLoad();
    });


    function loadPlanFeatures(planId, courseId) {
        fetch(`/plan-mapping/plan/${planId}/included-features?course_id=${courseId}`)
            .then(response => response.json())
            .then(data => {

                clearFeatureContainers();

                // INCLUDED
                const includedContainer = document.getElementById('included-features-container');
                (data.included || []).forEach(featureName => {
                    includedContainer.appendChild(
                        createFeatureDisplay(featureName, true)
                    );
                });

                // EXCLUDED
                const excludedContainer = document.getElementById('excluded-features-container');
                (data.excluded || []).forEach(featureName => {
                    excludedContainer.appendChild(
                        createFeatureDisplay(featureName, false)
                    );
                });
            })
            .catch(error => {
                console.error('Error fetching features:', error);
                showErrorState();
            });
    }


    function createFeatureDisplay(featureName, isIncluded) {
        const div = document.createElement('div');
        div.className =
            'flex items-center gap-2 p-2 bg-white border rounded-md shadow-sm';

        const iconClass = isIncluded ? 'bg-green-500' : 'bg-red-500';
        const icon = isIncluded ? 'ri-check-line' : 'ri-close-line';
        const hiddenName = isIncluded ? 'included_features[]' : 'excluded_features[]';

        div.innerHTML = `
        <div class="w-5 h-5 ${iconClass} rounded-full flex items-center justify-center">
            <i class="${icon} text-white text-xs"></i>
        </div>
        <span class="flex-1 text-sm font-medium">${featureName}</span>
        <input type="hidden" name="${hiddenName}" value="${featureName}">
    `;
        return div;
    }

    function clearFeatureContainers() {
        document.getElementById('included-features-container').innerHTML = '';
        document.getElementById('excluded-features-container').innerHTML = '';
    }

    function showErrorState() {
        document.getElementById('included-features-container').innerHTML =
            '<div class="text-red-500 text-sm p-3">Error loading included features</div>';

        document.getElementById('excluded-features-container').innerHTML =
            '<div class="text-red-500 text-sm p-3">Error loading excluded features</div>';
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
