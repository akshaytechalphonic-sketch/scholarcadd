@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Course Trainer Add',['common.home','Course.Course Trainer List','Course.Course Trainer Add'],['dashboard','course-trainer-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('course-trainer-update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name= "course_trainer_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('course-trainer-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Course Name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="course_id" class="select-single mt-1" style="width:100%" name="course_id" required>
                    <option value="" disabled>Select Course..</option>
                    @foreach ($course as $courses)
                        <option value="{{ $courses->id }}" @if (old('course_id', $data?->course_id) == $courses->id) selected @endif>
                            {{ $courses->course_title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Country Name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="country_id" class="select-single mt-1" style="width:100%" name="country_id" required>
                    <option value="" disabled>Select Course..</option>
                    @foreach ($course_Country as $courseCountry)
                        <option value="{{ $courseCountry->id }}" @if ($data?->country_id == $courseCountry->id) selected @endif>
                            {{ $courseCountry->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.State Name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="course_id" class="select-single mt-1" style="width:100%" name="state_id" required>
                    <option value="" disabled>Select Course..</option>
                    @foreach ($course_state as $course_state)
                        <option value="{{ $course_state->id }}" @if (old('state_id', $data?->state_id) == $course_state->id) selected @endif>
                            {{ $course_state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Trainer Name') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->trainer_name }}"
                    data-maxlength="200" name="trainer_name" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.trainer_experience') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="form-input mt-1" value="{{ $data?->trainer_experience }}"
                    data-maxlength="200" name="trainer_experience" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
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
                    {{-- Right: Uploaded Image Preview --}}
                    @if ($data?->image_path)
                        <div class="w-[160px]">

                            <div class="p-2 rounded-md border border-gray-300 bg-white">
                                <a target="_blank"
                                    href="{{ route('secure-documents', ['path' => $data?->image_path]) }}">
                                    <img class="h-[60px] w-full object-contain rounded-md"
                                        src="{{ route('secure-documents', ['path' => $data?->image_path]) }}"
                                        alt="Feature Image" style="width: 220px">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.trainer_experience') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea class="form-input mt-1" rows="5" cols="6" name="course_offered" required>{{ $data?->course_offered }}
                </textarea>
                {{-- <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" value=""
                name="course_offered">{{ $data?->course_offered }}</textarea> --}}

            </div>


        </div>
        <div class="w-full mt-4">
            <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }}
            </label><br />
            <br />
            <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" value=""
                name="short_description">{{ $data?->trainer_description }}</textarea>

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
