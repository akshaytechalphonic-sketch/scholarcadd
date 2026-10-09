@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>


        @php echo breadcrums('common.plan-add',['common.home','common.plan','common.plan-add'],['dashboard','plan-index']) @endphp
@section('back_button')
    @php echo back_button('plan-index') @endphp
@endsection

    <div class="text-xs w-full  bg-white border rounded-md p-4">
        {{-- @if ($data) --}}
        {{-- <form action="{{ route('course-location-update') }}" method="post">
                <input type="hidden" name="location_id" value="{{ encrypt($data?->id) }}"> --}}
        {{-- @else --}}
        @php
        @endphp
        <form action="{{ route('plan-store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-2">

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Name
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" name="name" data-maxlength="100"
                        required>
                </div>


                {{-- <div class="w-full">
                        <label class="text-sm font-semibold text-black">Included Features
                            <span class="text-red-500 font-bold">*</span></label>
                        <select class="select-multi mt-1" multiple style="width:100%" name="included-features[]"
                            required>
                            <option value="">Select Features...</option>
                            @foreach ($features as $feature)
                                <option value="{{ $feature->name }}">{{ $feature->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                {{-- <div class="w-full">
                    <label class="text-sm font-semibold text-black">Excluded Features
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-multi mt-1" multiple style="width:100%" name="excluded-features[]">
                        <option value="">Select Features...</option>
                        @foreach ($features as $feature)
                            <option value="{{ $feature->name }}">{{ $feature->name }}</option>
                        @endforeach
                    </select>
                </div> --}}


                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Base Discount %
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="coord-input form-input mt-1" name="baseDiscount" maxlength="2"
                        required>
                </div>



                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Recommended</label>
                    <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="recommended" required>
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>

                    </select>
                </div>

                <div class="w-full">
                    <label class="text-sm font-semibold text-black">is your basic Plan</label>
                    <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="is_basic" required>
                        <option value="">Select...</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>

                    </select>
                </div>

            </div>



           <div class="w-full flex gap-6">
    <div class="w-1/2">
        <label class="text-sm font-semibold text-black block mb-2">
            Included Features <span class="text-red-500 font-bold">*</span>
        </label>
        <div class="space-y-2" id="included-features-container">
            @foreach ($features as $feature)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="included-features[]" value="{{ $feature->description }}"
                        class="checkbox included-checkbox" data-feature="{{ $feature->name }}">
                    <span>{{ $feature->description }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="w-1/2">
        <label class="text-sm font-semibold text-black block mb-2">
            Excluded Features <span class="text-red-500 font-bold">*</span>
        </label>
        <div class="space-y-2" id="excluded-features-container">
            @foreach ($features as $feature)
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="excluded-features[]" value="{{ $feature->name }}"
                        class="checkbox excluded-checkbox" data-feature="{{ $feature->name }}">
                    <span>{{ $feature->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>




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
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@endsection

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
