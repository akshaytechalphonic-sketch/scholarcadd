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
        @if (!empty($data) && $data->id)
            <form action="{{ route('course-category-update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name= "course_category_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('course-category-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Category Name') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->category_name }}"
                    data-maxlength="200" name="category_name" required>
                </input>
               </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('Course.page link') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->page_link }}"
                    data-maxlength="200" name="page_link" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Parent Category Name
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="hidden" class="limited-input form-input mt-1" value="{{ $data?->parent?->id }}"
                    data-maxlength="100" name="parent_category_id" readonly>
                <input type="text" class="limited-input form-input mt-1"
                    value="{{ $data->parent->category_name ?? 'No Parent' }}" data-maxlength="100" name="state"
                    readonly>

                
            </div>
            {{-- ///////// Feature Image Upload --}}
            <div class="w-full">
                <label for="feature_image" class="text-sm font-semibold text-black">
                    {{ __('Course.Feature Image') }}
                </label>
                <div class="flex items-start gap-6 mt-2">
                    {{-- Left: File Input --}}
                    <div class="flex-1">
                        <input id="feature_image" type="file" class="form-input mt-1 w-full" name="feature_image"
                            accept=".jpg, .jpeg, .png">
                        <p id="feature-image-error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    <input type="hidden" class="form-input mt-1 w-full" name="Course_image"
                        value="{{ $data?->Course_image }}">
                    {{-- Right: Uploaded Image Preview --}}
                    @if ($data?->mainImages)
                        <div class="w-[160px]">

                            <div class="p-2 rounded-md border border-gray-300 bg-white">
                                <a target="_blank"
                                    href="{{ route('secure-documents', ['path' => $data?->mainImages]) }}">
                                    <img class="h-[60px] w-full object-contain rounded-md"
                                        src="{{ route('secure-documents', ['path' => $data?->mainImages]) }}"
                                        alt="Feature Image" style="width: 220px">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>





        </div>
        <div class="w-full mt-4">
            <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }}
            </label><br />
            <br />
            <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" value=""
                name="short_description">{{ $data?->short_description }}</textarea>

        </div>


    </div>
</div>
<br />
<div class="text-xs w-full  bg-white border rounded-md p-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full my-2">

        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">Scholaracad Advantage (Headline)

            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->scholaracad_advantage }}"
                data-maxlength="100" name="scholaracad_advantage">

        </div>
        {{-- ///////// Scholaracad Advantage --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 1
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option1 }}"
                data-maxlength="100" name="Option1">
        </div>
        {{-- ///////// Scholaracad Advantage --}}

        {{-- ///////// Scholaracad Advantage  Option2 --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 2
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option2 }}"
                data-maxlength="100" name="Option2">
        </div>
        {{-- ///////// Scholaracad Advantage --}}
        {{-- ///////// Scholaracad Advantage  Option3 --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 3
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option3 }}"
                data-maxlength="100" name="Option3">

            <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
        </div>
        {{-- ///////// Scholaracad Advantage --}}
        {{-- ///////// Scholaracad Advantage  Option4 --}}
        <div class="w-full">
            <label for="Option 4" class="text-sm font-semibold text-black">
                Option 4
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option4 }}"
                data-maxlength="100" name="Option4">
        </div>
        {{-- ///////// Scholaracad Advantage --}}
        {{-- ///////// Scholaracad Advantage  Option2 --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 5
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option5 }}"
                data-maxlength="100" name="Option5">
        </div>
        {{-- ///////// Scholaracad Advantage --}}
        {{-- ///////// Scholaracad Advantage  Option2 --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 6
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option6 }}"
                data-maxlength="100" name="Option6">        </div>
        {{-- ///////// Scholaracad Advantage --}}
        {{-- ///////// Scholaracad Advantage  Option2 --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 7
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option7 }}"
                data-maxlength="100" name="Option7">
        </div>
        {{-- ///////// Scholaracad Advantage --}}
        {{-- ///////// Scholaracad Advantage  Option2 --}}
        <div class="w-full">
            <label for="feature_image" class="text-sm font-semibold text-black">
                Option 8
            </label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Option8 }}"
                data-maxlength="100" name="Option8">
        </div>
        {{-- ///////// Scholaracad Advantage --}}
        <div class="relative z-10 w-full">
            <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Status') }}
                <span class="text-red-500 font-bold">*</span></label>
            <select class="select-single mt-1" style="width:100%" name="status" required>
                <option value=""></option>
                <option value="Active" @if ($data?->status == 'Active') selected @endif>Active</option>
                <option value="Inactive" @if ($data?->status == 'Inactive') selected @endif>In Active </option>
            </select>
        </div>
    </div>
</div>

<br />

<div class="text-xs w-full  bg-white border rounded-md p-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full my-2">

        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black"> Knowledge Partner (Headline)

            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->knowledge_partner }}"
                data-maxlength="100" name="knowledge_partner">
        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black"> knowledge Button Lable

            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->knowledge_button }}"
                data-maxlength="100" name="knowledge_button">

        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black"> knowledge Button Link

            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->knowledge_link }}"
                data-maxlength="100" name="knowledge_link">
        </div>
        {{-- ///////// Feature Image Upload --}}
        <div class="w-full">
            <label for="knowledge_image" class="text-sm font-semibold text-black">
                {{ __('Course.Feature Image') }}
            </label>
            <div class="flex items-start gap-6 mt-2">
                {{-- Left: File Input --}}
                <div class="flex-1">
                    <input id="knowledge_images" type="file" class="form-input mt-1 w-full"
                        name="knowledge_images" accept=".jpg, .jpeg, .png">
                    <input type="hidden" class="form-input mt-1 w-full" name="knowledge_images"
                        value="{{ $data?->knowledge_images }}">


                    <p id="knowledge-image-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                {{-- Right: Uploaded Image Preview --}}
                @if ($data?->knowledge_images)
                    <div class="w-[160px]">

                        <div class="p-2 rounded-md border border-gray-300 bg-white">
                            <a target="_blank"
                                href="{{ route('secure-documents', ['path' => $data?->knowledge_images]) }}">
                                <img class="h-[60px] w-full object-contain rounded-md"
                                    src="{{ route('secure-documents', ['path' => $data?->knowledge_images]) }}"
                                    alt="Feature Image" style="width: 220px">
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="w-full mt-4">
        <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }}
        </label><br />
        <br />
        <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" value=""
            name="knowledge_description">{{ $data?->knowledge_description }}</textarea>

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
