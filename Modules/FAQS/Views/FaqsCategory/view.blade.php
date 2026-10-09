@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.FAQS-Category-view'),[__('common.home'), __('Course.FAQS-Category-List'), __('Course.FAQS-Category-view')],['dashboard','course-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('faqs-category-edit', ['id' => encrypt($data->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3 space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.FAQS-Category-name') }} 
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->category_name }}"
                    data-maxlength="100" name="faqs_category" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.tab2_js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
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

    // Apply validation
    validateFileInput('feature_image', 'feature-image-error', ['image/jpeg', 'image/png'], 2);
    validateFileInput('brochure_file', 'brochure-error', ['application/pdf'], 2);
</script>
@endsection
