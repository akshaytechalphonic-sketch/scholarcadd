@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection

@php
$included = json_decode($plan->included_features, true) ?? [];
$excluded = json_decode($plan->excluded_features, true) ?? [];
@endphp

@section('content')
<div>
@section('back_button')
    @php echo back_button('plan-index') @endphp
@endsection

    <h3 class="text-lg p-4 my-2 w-full border bg-white rounded-md">
        {{ $flag }} Plan
    </h3>

    <div class="text-xs w-full bg-white border rounded-md p-4">
        @php
            $readonly = $flag == 'View' ? 'readonly' : '';
        @endphp

        <form action="{{ route('plan.update', encrypt($plan['id'])) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-2">
                {{-- Name --}}
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">
                        Name <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input {{ $readonly }} type="text" class="limited-input form-input mt-1 w-full"
                           name="name" value="{{ old('name', $plan['name']) }}"
                           data-maxlength="100" required>
                </div>

                {{-- Base Discount --}}
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">
                        Base Discount % <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input {{ $readonly }} type="text" class="coord-input form-input mt-1 w-full"
                           name="baseDiscount" maxlength="2"
                           value="{{ old('baseDiscount', $plan['baseDiscount']) }}" required>
                </div>

                {{-- Recommended --}}
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Recommended</label>
                    <select class="select-single mt-1 w-full" name="recommended" required {{ $readonly }}>
                        <option value="">Select...</option>
                        <option value="yes" {{ old('recommended', $plan['recommended']) == 'yes' ? 'selected' : '' }}>
                            Yes
                        </option>
                        <option value="no" {{ old('recommended', $plan['recommended']) == 'no' ? 'selected' : '' }}>
                            No
                        </option>
                    </select>
                </div>

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">is your basic Plan</label>
                    <select class="select-single mt-1" style="width:100%" name="is_basic" required>
                        <option value="">Select...</option>
                        <option value="yes"  {{ old('is_basic', $plan['is_basic']) == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no"  {{ old('is_basic', $plan['is_basic']) == 'no' ? 'selected' : '' }}>No</option>

                    </select>
                </div>

            </div>

            <div class="w-full flex gap-6 mt-4">
                <div class="w-1/2">
                    <label class="text-sm font-semibold text-black block mb-2">
                        Included Features <span class="text-red-500 font-bold">*</span>
                    </label>
                    <div class="space-y-2" id="included-features-container">
                       @foreach ($features as $feature)
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                    name="included-features[]"
                                    value="{{ $feature->description }}"
                                    class="checkbox included-checkbox"
                                    data-feature="{{ $feature->name }}"
                                    @checked(in_array($feature->description, $included))
                                    {{ $readonly ? 'disabled' : '' }}>
                                <span>{{ $feature->description }}</span>
                            </label>
                        @endforeach

                    </div>
                </div>

                <div class="w-1/2">
                    <label class="text-sm font-semibold text-black block mb-2">
                        Excluded Features
                    </label>
                    <div class="space-y-2" id="excluded-features-container">
                        @foreach ($features as $feature)
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                       name="excluded-features[]"
                                       value="{{ $feature->name }}"
                                       class="checkbox excluded-checkbox"
                                       data-feature="{{ $feature->name }}"
                                       {{ in_array($feature->name, $excluded) ? 'checked' : '' }}
                                       {{ $readonly ? 'disabled' : '' }}>
                                <span>{{ $feature->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Submit / Reset Buttons --}}
            @if ($flag == 'Edit')
                <div class="flex justify-end mt-6">
                    <div class="flex gap-2">
                        <button type="reset" class="btn-primary-sm flex items-center gap-1">
                            <i class="ri-refresh-line"></i> {{ __('common.reset') }}
                        </button>
                        <button type="submit" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i> {{ __('common.submit') }}
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @include('layouts.select2js')
    @include('layouts.summer_note_js')
    @include('layouts.image_validation_js')

    <script>
        function escapeCSS(str) {
            return str.replace(/([#.:*+?^$[\]\\(){}|<>-])/g, '\\$1');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle Included Features
            document.querySelectorAll('.included-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureName = this.getAttribute('data-feature');
                    const escapedFeatureName = escapeCSS(featureName);
                    const excludedCheckbox = document.querySelector(`.excluded-checkbox[data-feature="${escapedFeatureName}"]`);
                    if (excludedCheckbox) {
                        if (this.checked) {
                            excludedCheckbox.disabled = true;
                            excludedCheckbox.checked = false;
                        } else {
                            excludedCheckbox.disabled = false;
                        }
                    }
                });
            });

            // Handle Excluded Features
            document.querySelectorAll('.excluded-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureName = this.getAttribute('data-feature');
                    const escapedFeatureName = escapeCSS(featureName);
                    const includedCheckbox = document.querySelector(`.included-checkbox[data-feature="${escapedFeatureName}"]`);
                    if (includedCheckbox) {
                        if (this.checked) {
                            includedCheckbox.disabled = true;
                            includedCheckbox.checked = false;
                        } else {
                            includedCheckbox.disabled = false;
                        }
                    }
                });
            });

            // On page load, disable conflicting checkboxes
            document.querySelectorAll('.included-checkbox:checked').forEach(checkbox => {
                const featureName = checkbox.getAttribute('data-feature');
                const escapedFeatureName = escapeCSS(featureName);
                const excludedCheckbox = document.querySelector(`.excluded-checkbox[data-feature="${escapedFeatureName}"]`);
                if (excludedCheckbox) {
                    excludedCheckbox.disabled = true;
                }
            });

            document.querySelectorAll('.excluded-checkbox:checked').forEach(checkbox => {
                const featureName = checkbox.getAttribute('data-feature');
                const escapedFeatureName = escapeCSS(featureName);
                const includedCheckbox = document.querySelector(`.included-checkbox[data-feature="${escapedFeatureName}"]`);
                if (includedCheckbox) {
                    includedCheckbox.disabled = true;
                }
            });
        });
    </script>
@endsection
