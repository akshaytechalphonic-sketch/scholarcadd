@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.View Course'),[__('common.home'), __('Course.Course List'), __('Course.View Course')],['dashboard','course-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('course-edit', ['id' => encrypt($data->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3 space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label class="block text-sm font-semibold text-gray-700">
        {{ __('Course.Course Name (Short Form)') }}
        <span class="text-red-500">*</span>
    </label>
    <div class="mt-1 w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800">
        {{ $data?->course_title ?? '—' }}
    </div>
            </div>

            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Category') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="category_id" class="select-single mt-1" style="width:100%" name="course_category_id"
                    required>
                    <option value="" disabled>Select Category..</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if (old('category_id', $data?->course_category_id) == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Url Title') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->url_title }}"
                    data-maxlength="100" name="url_title" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Title') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->course_title }}"
                    data-maxlength="100" name="course_title" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Banner BG Color') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="color" class="limited-input form-input mt-1" value="{{ $data?->banner_bg_color }}"
                    data-maxlength="100" name="banner_bg_color" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
            </div>


            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Is Demand Course ?') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <select class="select-single mt-1" style="width:100%" name="is_demand_course" required>
                    <option value="Yes" @if ($data?->is_demand_course == 'Yes' || !$data) selected @endif>Yes </option>
                    <option value="No" @if ($data?->is_demand_course == 'No') selected @endif>No </option>

                    </option>
                </select>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Short Description') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea type="text" class="form-input mt-1 description-validation" value="" name="short_description"
                    data-maxlength="200">{{ $data?->short_description }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>

            </div>





        </div>
        <hr />




        <div class="tab-wrapper flex-1 mt-4 rounded-md">
            <div class="tabs mb-2 bg-gray-50">
                <div class="flex flex-wrap gap-2 w-full border-b border-gray-200">
                    <button type="button" data-tab="1"
                        class="tab-btn flex-none text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium">
                        {{ __('Course.Overview') }} </button>
                    <button type="button" data-tab="2"
                        class="tab-btn flex-none text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium">
                        {{ __('Course.Training Options') }} </button>
                    <button type="button" data-tab="3"
                        class="tab-btn flex-none text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium">
                        {{ __('Course.Curriculum') }} </button>
                    <button type="button" data-tab="4"
                        class="tab-btn flex-none text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium">
                        {{ __('Course.Eligibility') }} </button>
                    <button type="button" data-tab="5"
                        class="tab-btn flex-none text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium">
                        {{ __('Course.SEO Content') }} </button>


                </div>
            </div>
            <div class=" ">
                <div class="tab-content  rounded-sm  ">
                    <div data-content="1" class="tab-pane hidden ">
                        <div class="p-2">
                            <div class="w-full mt-4">
                                <label for=""
                                    class="text-sm font-semibold text-black">{{ __('Course.Overview') }}
                                </label>
                                <div class="mt-3">
                                    <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" name="overview">{!! $data?->overview !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-content="2" class="tab-pane hidden">
                        <div class="p-2">
                            <div class="w-full mt-4">
                                <label for=""
                                    class="text-sm font-semibold text-black">{{ __('Course.Training Options') }}
                                </label>
                                <div class="mt-3">
                                    <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                        name="training_options">{!! $data?->training_options !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-content="3" class="tab-pane hidden">
                        <div class="p-2">
                            <div class="w-full mt-4">
                                <label for=""
                                    class="text-sm font-semibold text-black">{{ __('Course.Course Curriculum') }}
                                </label>
                                <div class="mt-3">
                                    <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                        name="course_curriculum">{!! $data?->course_curriculum !!}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div data-content="4" class="tab-pane hidden">
                        <div class="p-2">
                            <div class="w-full mt-4">
                                <label for=""
                                    class="text-sm font-semibold text-black ">{{ __('Course.Course Details') }}
                                </label>
                                <div class="mt-3">
                                    <textarea type="text" class="form-input  description-validation-summernote summernote" name="course_details">{!! $data?->course_details !!}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div data-content="5" class="tab-pane hidden">
                        <div class="p-2">
                            <div class="w-full mt-4">



                                <div class="w-full">
                                    <label for=""
                                        class="text-sm font-semibold text-black">{{ __('Course.Page Title') }}
                                        <span class="text-red-500 font-bold">*</span></label>
                                    <input type="text" class="limited-input form-input mt-1"
                                        value="{{ $data?->page_title }}" data-maxlength="100" name="page_title"
                                        required>
                                    </input>
                                    <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
                                </div>
                                <div class="w-full">
                                    <label for=""
                                        class="text-sm font-semibold text-black">{{ __('Course.Meta Keywords') }}
                                        <span class="text-red-500 font-bold">*</span></label>
                                    <textarea type="text" class="form-input mt-1 description-validation" value="" name="meta_keywords"
                                        data-maxlength="200">{{ $data?->meta_keywords }}</textarea>
                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                </div>
                                <div class="w-full">
                                    <label for=""
                                        class="text-sm font-semibold text-black">{{ __('Course.Meta Description') }}
                                        <span class="text-red-500 font-bold">*</span></label>
                                    <textarea type="text" class="form-input mt-1 description-validation" value="" name="meta_description"
                                        data-maxlength="200">{{ $data?->meta_description }}</textarea>
                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <hr />


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
