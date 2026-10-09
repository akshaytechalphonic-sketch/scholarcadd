@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Course Category Add',['common.home','Course.Course Category List','Course.Course Category Add'],['dashboard','course-category-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">

        <form action="{{ route('course-childcategory-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Category Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" data-maxlength="200" name="category_name"
                        required>

                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('Course.page link') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" data-maxlength="200" name="page_link"
                        required>

                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Parent Category Name
                        <span class="text-red-500 font-bold">*</span></label>

                    <select class="select-single mt-1" style="width:100%" name="parent_category_id" required>
                        <option value=""></option>
                        @foreach ($category as $categoryData)
                            <option value="{{ $categoryData?->id }}">
                                {{ $categoryData?->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative z-10 w-full">
                    <label for="" class=" text-sm font-semibold text-black">Position
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" data-maxlength="200" name="position"
                        required>

                </div>
                <div class="relative z-10 w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Status') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="status" required>

                        <option value="Active">Active</option>
                        <option value="Inactive">In Active </option>
                    </select>
                </div>
            </div>
            <div class="w-full mt-4">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }}
                </label><br />
                <br />
                <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" value=""
                    name="short_description"></textarea>

            </div>


    </div>
</div>

<br />

<div class="text-xs w-full  bg-white border rounded-md p-4">



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
{{-- @include('layouts.select2js') --}}
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
    validateFileInput('knowledge_images', 'feature-image-error', ['image/jpeg', 'image/png'], 2);
</script>
@endsection
