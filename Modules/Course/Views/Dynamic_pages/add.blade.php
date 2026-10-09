@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
    <style>

    </style>
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Course Add', ['common.home', 'Course.Course List', 'Course.Course Add'], ['dashboard', 'bank-list']); @endphp
    @section('back_button')
        @php echo back_button('dynamic-course-pages-list') @endphp
    @endsection
</div>
@if ($data)
    <form action="{{ route('dynamic-course-pages-update') }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name= "dynamiccourse_pages_id" value = "{{ encrypt($data?->id) }}">
    @else
        <form action="{{ route('dynamic-course-pages-store') }}" method="post" enctype="multipart/form-data">
@endif
@csrf
<div class="mt-4">
    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md"
        open>
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Course Information</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-4">
                <div style="display:none">
                    <label for="course_name" class="text-sm font-semibold text-black">
                        Course Name
                    </label>

                    <input type="text" name="name" id="course_name"
                        value="{{ $data?->Coursedate?->course_short_name }}"
                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                        data-maxlength="100" />
                    <p id="course-name-count" class="mt-1 text-xs text-gray-400 text-right">0 / 100</p>
                </div>
                <div>
                    <label for="category_id" class="text-sm font-semibold text-black">
                        Category
                    </label>
                    <select name="category_id"
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Category</option>
                        @foreach ($course as $courses)
                            <option value="{{ $courses->id }}" @if (old('category_id', $data?->course_id) == $courses->id) selected @endif>
                                {{ $courses->course_title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="text-sm font-semibold text-black">
                        Status
                    </label>
                    <select name="status" id="status"
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="Active" @if ($data?->status == 'Active' || !$data) selected @endif>Active
                        </option>
                        <option value="In active" @if ($data?->status == 'In active') selected @endif>Inactive
                        </option>
                        <option value="Discontinued" @if ($data?->status == 'Discontinued') selected @endif>
                            Discontinued</option>
                    </select>
                </div>
            </div>


            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-4">

                <div>
                    <label for="category_id" class="text-sm font-semibold text-black">
                        Country Name
                    </label>
                    <select name="country_id" id="country_id"
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Country</option>
                        @foreach ($course_Country as $course_Country)
                            <option value="{{ $course_Country->id }}" @if (old('country_id', $data?->country_id) == $course_Country->id) selected @endif>
                                {{ $course_Country?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="state_id" class="text-sm font-semibold text-black">
                        State Name
                    </label>
                    <select name="state_id" id="state_id"
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Sub State</option>
                    </select>
                </div>

            </div>

            <div class="mt-4">
                <label class="text-sm font-semibold text-black">Main Heading - (H1 Headline)</label>
                <input placeholder="Enter Main Heading" name="hone_tag"
                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                    data-maxlength="200" value="{{ $headline?->hone_tag }}">
                <div id="tagline-count" class="char-count text-xs text-gray-500 text-right mt-1">0 / 200</div>
            </div>
            <div class="">
                <label for="tagline" class="text-sm font-semibold text-black">Tagline
                </label>
                <textarea id="tagline" name="tagline" placeholder="Enter tagline"
                    class="description-validation form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                    data-maxlength="200" rows="2">{{ $bulletPoints?->course_description }}</textarea>
                <div id="tagline-count" class="char-count text-xs text-gray-500 text-right mt-1">0 / 200</div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-4 gap-4">
                <div class="">
                    <label class="text-sm font-semibold text-black">Google Rating</label>
                    <input placeholder=""
                        class="form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                        data-maxlength="10" name="googleRating" value="{{ $data?->googleRating ?? '4.9/5' }}">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0 / 10</div>
                </div>
                <div class="">
                    <label class="text-sm font-semibold text-black">Facebook Rating</label>
                    <input placeholder=""
                        class="form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                        data-maxlength="10" name="facebook" value="{{ $data?->facebook ?? '4.8/5' }}">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0 / 10</div>
                </div>
                <div class="">
                    <label class="text-sm font-semibold text-black">Instagram Rating</label>
                    <input placeholder=""
                        class="form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                        data-maxlength="10" name="installgram" value="{{ $data?->installgram ?? '4.9/5' }}">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0 / 10</div>
                </div>
                <div class="">
                    <label class="text-sm font-semibold text-black">Learners Enrolled</label>
                    <input placeholder=""
                        class="form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                        data-maxlength="10" name="enrolled"
                        value="{{ $data?->enrolled ?? '6,000 + Learners Enrolled' }}">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0 / 10</div>
                </div>
            </div>
            <div class="relative mt-4">
                <label class="text-sm font-semibold text-black mb-1 block">
                    Feature Image
                </label>

                <!-- DROP AREA -->
                <div id="feature-image-drop-area"
                    class="flex items-center justify-center w-full h-32 sm:h-36
           border-2 border-dashed border-gray-400 rounded-xl
           bg-gray-50 cursor-pointer
           hover:border-blue-500 hover:bg-gray-100
           transition relative">

                    <input type="file" name="feature_image" id="feature_image" accept="image/*" class="hidden">

                    <!-- 👇 IMPORTANT -->
                    <span id="upload-text" class="text-gray-400 text-sm sm:text-base pointer-events-none">
                        Drag & drop or click to upload
                    </span>
                </div>


                <!-- ERROR -->
                <p id="feature-image-error" class="text-xs text-red-500 mt-1 hidden"></p>

                <!-- PREVIEW -->
                <div id="feature_image_preview" class="mt-2 hidden">
                    <img id="preview-img" class="h-20 sm:h-24 rounded-md border object-contain" src=""
                        alt="Preview">
                </div>

                <!-- REMOVE BUTTON -->
                <button type="button" id="remove-image"
                    class="hidden mt-2 px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition">
                    Remove
                </button>
            </div>


            @if (!empty($course_image?->banner_image_path))
                <div>
                    <label class="text-sm font-semibold text-black">
                        Uploaded Image
                    </label>

                    <div class="p-3 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center">
                        <a href="{{ asset($course_image?->banner_image_path) }}" target="_blank">
                            <img src="{{ asset($course_image?->banner_image_path) }}"
                                class="h-20 sm:h-24 object-contain rounded-md" alt="Uploaded Image">
                        </a>
                    </div>
                </div>
            @endif

            <div>
                <label class="text-sm font-semibold text-black">Bullet Points</label>
                <div id="bulletPoints" class="space-y-3">
                    <div
                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">

                        <input type="text" name="bulletArray[]" value="{{ $bulletArray[0] ?? '' }}"
                            placeholder="Enter bullet point"
                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                            aria-label="Bullet point"
                            value="{{ json_encode(data_get($bulletPoints, 'course_highlights_in_pointers', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}">
                        <button onclick="deleteItem(this)"
                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <i class="ri-close-fill"></i>
                        </button>

                    </div>
                    <div
                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                        <input type="text" name="bulletArray[]" value="{{ $bulletArray[1] ?? '' }}"
                            placeholder="Enter bullet point"
                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                            aria-label="Bullet point">
                        <button onclick="deleteItem(this)"
                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <i class="ri-close-fill"></i>
                        </button>
                    </div>
                    <div
                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                        <input type="text" name="bulletArray[]" value="{{ $bulletArray[2] ?? '' }}"
                            placeholder="Enter bullet point"
                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                            aria-label="Bullet point">
                        <button onclick="deleteItem(this)"
                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <i class="ri-close-fill"></i>
                        </button>
                    </div>
                    <div
                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                        <input type="text" name="bulletArray[]" value="{{ $bulletArray[3] ?? '' }}"
                            placeholder="Enter bullet point"
                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                            aria-label="Bullet point">
                        <button onclick="deleteItem(this)"
                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <i class="ri-close-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-8 text-end">
                    <button type="button" onclick="addItem('bulletPoints')" class="btn-primary-sm ">
                        <i class="ri-add-line mr-2"></i>Add item</button>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-700 bg-white  border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-4 gap-4">
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">First Button Label
                            <input type="text"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                data-maxlength="100" name="first_button_label">
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Link For First Button
                        </label>
                        <input type="text"
                            id="Heading"class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                            data-maxlength="100" name="link_first_button">
                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Second Button Label
                        </label>
                        <input type="text"
                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                            data-maxlength="100" name="secound_button_label">
                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Link For Second Button
                        </label>
                        <input type="text"
                            id="Heading"class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                            data-maxlength="100" name="link_secound_button">
                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                    </div>
                </div>
            </div>
        </div>
    </details>
</div>
<div class="mt-4">
    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md">
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Tabs</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">

            <div class="tab-wrapper flex-1 mt-4 rounded-xl">
                <div class="tabs mb-8">
                    <div class="flex flex-wrap  bg-gray-100 shadow-sm rounded-t-lg overflow-hidden">
                        <button type="button" data-tab="1"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            {{ __('Course.Course Highlights') }}
                        </button>
                        <button type="button" data-tab="2"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            Why Choose Us
                        </button>
                        <button type="button" data-tab="3"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            {{ __('Course.Curriculum') }}
                        </button>
                        <button type="button" data-tab="4"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            {{ __('Course.Eligibility') }}
                        </button>

                        <button type="button" data-tab="6"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            {{ __('Course.roadmap') }}
                        </button>
                        <button type="button" data-tab="7"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            Learning Objective
                        </button>
                        <button type="button" data-tab="8"
                            class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                            {{ __('Course.Course Review') }}
                        </button>

                    </div>

                    <div class=" ">
                        <div class="tab-content  rounded-sm  ">
                            <div data-content="1" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full mt-4">
                                        <label for="Heading" class="text-sm font-semibold text-black">Heading
                                            <input type="text" id="Heading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="course_highlights_heading"
                                                value="{{ $allHeadline?->program_overview }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                    </div>
                                    <div class="w-full">

                                        <label for="" class="text-sm font-semibold text-black">Course
                                            Overview</label>
                                        <textarea type="text" name="course_highlights_overview"
                                            class="form-input  description-validation-summernote summernote" name="certificate-description">{!! $courseOverview?->course_overview !!}</textarea>

                                    </div>
                                    <div class="w-full mb-6 mt-6">
                                        <label for="" class="text-sm font-semibold text-black mt-2 ">
                                            Key Feature Headline </label>

                                        <input type="text" id="Heading"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="training_highlights"
                                            value="{{ $allHeadline?->training_highlights }}">

                                    </div>
                                    <div class="w-full mb-6 mt-6">
                                        <hr />
                                    </div>
                                    <div class="w-full  mb-6 ">
                                        <h4 class="text-sm font-semibold text-black mt-2"> Key Feature Points
                                        </h4>
                                    </div>
                                    <div class="w-full">
                                        <input type="text" id="pointOne"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="100" name="record_one"
                                            value="{{ $keyfeatures?->record_one }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <input type="text" id="pointTwo"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="100" name="point_two"
                                            value="{{ $keyfeatures?->record_two }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <input type="text" id="pointThree"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="100" name="point_three"
                                            value="{{ $keyfeatures?->record_three }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <input type="text" id="pointFour"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="100" name="point_four"
                                            value="{{ $keyfeatures?->record_four }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <input type="text" id="pointFive"
                                            class="count-validation form-input mt-1 mb-4 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="100" name="point_five"
                                            value="{{ $keyfeatures?->record_five }}">
                                    </div>
                                    <div class="w-full">
                                        <input type="text"
                                            class="form-input mt-1 w-full border border-gray-300 rounded-md p-2"
                                            data-maxlength="100" name="keyfeatures_point_six"
                                            value="{{ $keyfeatures?->record_six }}">
                                    </div>
                                    <br />
                                    <div class="w-full">
                                        <label for="pointSixDescription" class="text-sm font-semibold text-black">
                                            Read More
                                        </label>
                                        <textarea type="text" id="keyfeatures_readmore"
                                            class="form-input mt-1 description-validation-summernote summernote" name="keyfeatures_readmore">{!! $keyfeatures?->read_more ?? '' !!}</textarea>



                                    </div>
                                    <div class="w-full mb-6 mt-6">
                                        <hr />
                                    </div>
                                    <div id="whatyoulearn-points">
                                        <div class="w-full mb-6 mt-6 ">
                                            <h4 class="text-sm font-semibold text-black mt-2"> What You Learn Headline
                                            </h4>
                                        </div>

                                        <div class="w-full mb-6 mt-6">
                                            <input type="text" id="pointFive"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="100" name="new_learning_objectives"
                                                value="{{ $allHeadline?->new_learning_objectives }}">
                                        </div>
                                        @if (!empty($whatyoulearn?->pointers) && is_array($whatyoulearn->pointers))
                                            @foreach ($whatyoulearn->pointers as $points)
                                                <div
                                                    class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px mb-3">
                                                    <input type="text" name="pointers[]"
                                                        value="{{ $points }}" placeholder="Enter point"
                                                        class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                                                        aria-label="Bullet point">
                                                    <button onclick="deleteItem_whatweLearn(this)"
                                                        class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                        <i class="ri-close-fill"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- minimum one field --}}
                                            <div
                                                class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                                                <input type="text" name="pointers[]" placeholder="Enter point"
                                                    class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                                                    aria-label="Bullet point">
                                                <button onclick="deleteItem_whatweLearn(this)"
                                                    class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                    <i class="ri-close-fill"></i>
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="mt-8 text-end">
                                        <button type="button" onclick="addItemwhatweLearn('whatyoulearn-points')"
                                            class="btn-primary-sm ">
                                            <i class="ri-add-line mr-2"></i>
                                            Add item
                                        </button>
                                    </div>
                                    <div class="w-full  mb-6 ">
                                        <label for=""
                                            class="text-sm font-semibold text-black mb-6 ">Description</label>
                                        <br />
                                        <br />
                                        <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                            name="whatyoulearn_description">{!! $whatyoulearn?->description !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-content="2" class="tab-pane hidden">
                    <div class="p-2">
                        <div class="w-full">
                            <div class="col-span-1 flex flex-col mb-4 p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="w-full">
                                    <label for="Heading" class="text-sm font-semibold text-black">Heading (H2
                                        Headline)
                                        <input type="text" id="Heading"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="scholaracad_heading"
                                            value="{{ $allHeadline?->advantage }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointOneHeading"
                                                class="text-sm font-semibold text-black">Point One
                                                Heading
                                                <input type="text"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_one_heading"
                                                    value="{{ $records['record_one']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointOneDescription"
                                                class="text-sm font-semibold text-black">Point
                                                One Description
                                                <textarea type="text" rows="7"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_one_description">{{ $records['record_one']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointTwoHeading"
                                                class="text-sm font-semibold text-black">Point Two
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_two_heading"
                                                    value="{{ $records['record_two']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointTwoDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Two Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_two_description"> {{ $records['record_two']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointThreeHeading"
                                                class="text-sm font-semibold text-black">Point
                                                Three Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointThreeHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_three_heading"
                                                    value="{{ $records['record_three']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointThreeDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Three Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointThreeDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_three_description">{{ $records['record_three']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointFourHeading"
                                                class="text-sm font-semibold text-black">Point Four
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointFourHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_four_heading"
                                                    value="{{ $records['record_four']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointFourDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Four Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointFourDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_four_description">{{ $records['record_four']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg  bg-white">
                                        <div class="w-full">
                                            <label for="pointFiveHeading"
                                                class="text-sm font-semibold text-black">Point Five
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointFiveHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_five_heading"
                                                    value="{{ $records['record_five']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointFiveDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Five Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointFiveDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_five_description">{{ $records['record_five']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointSixHeading"
                                                class="text-sm font-semibold text-black">Point Six
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointSixHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_six_heading"
                                                    value="{{ $records['record_six']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointSixDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Six Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointSixDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_six_description">{{ $records['record_six']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    {{-- #--- for seven code  --}}
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointSevenHeading"
                                                class="text-sm font-semibold text-black">Point Seven
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointSevenHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_seven_heading"
                                                    value="{{ $records['record_seven']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>

                                        <div class="w-full">
                                            <label for="pointSixDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Seven Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointSevenDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_seven_description">{{ $records['record_seven']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    {{-- #-----end seven code  --}}
                                    {{-- #--- for Eight code  --}}
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointeightHeading"
                                                class="text-sm font-semibold text-black">Point Eight
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointeightHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_eight_heading"
                                                    value="{{ $records['record_eight']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointEightDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Eight Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointEightDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_eight_description">{{ $records['record_eight']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>

                                    {{-- #-----end seven code  --}}


                                    {{-- #--- for Eight code  --}}
                                    <div
                                        class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="w-full">
                                            <label for="pointeightHeading"
                                                class="text-sm font-semibold text-black">Point Nine
                                                Heading
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <input type="text" id="pointeightHeading"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="200" name="point_nine_heading"
                                                    value="{{ $records['record_nine']['heading'] ?? null }}">
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                </div>
                                        </div>
                                        <div class="w-full">
                                            <label for="pointNineDescription"
                                                class="text-sm font-semibold text-black">Point
                                                Nine Description
                                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                                <textarea type="text" rows="7" id="pointNineDescription"
                                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="500" name="point_nine_description">{{ $records['record_nine']['description'] ?? null }}</textarea>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                </div>
                                        </div>
                                    </div>
                                    {{-- #-----end seven code  --}}




                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-content="3" class="tab-pane hidden">
                    <div class="p-2">
                        <div class="w-full mb-4">
                            <label for="Heading" class="text-sm font-semibold text-black">Curriculum Heading (H2
                                Headline)
                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}

                                <input type="text" id="pointSixHeading"
                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                    data-maxlength="200" name="allHeadline_curriculum"
                                    value="{{ $allHeadline?->curriculum }}">
                        </div>
                        <div class="w-full mb-4">
                            <label for="Heading" class="text-sm font-semibold text-black">Curriculum Description
                                {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                        </div>

                        <div class="w-full mt-4">
                            <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                name="certificate_description"> {!! $courseHeadingDescription?->curriculum !!}

                            </textarea>
                        </div>
                        <div class="w-full mb-4 mt-8">
                            <label for="Heading" class="text-sm font-semibold text-black">Curriculum Points
                            </label>
                        </div>

                        @foreach ($coursecurriculumFaq as $key => $coursecurriculumFaqs)
                            <div class="w-full mb-4">
                                <input type="text" id="pointSixHeading"
                                    class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                    data-maxlength="200" name="coursecurriculumFaqs[]"
                                    value="{{ $coursecurriculumFaqs?->title }}">
                            </div>
                            <div class="w-full mb-4">
                                <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                    name="coursecurriculumFaqsdescription[]">{!! $coursecurriculumFaqs?->description !!}</textarea>
                            </div>
                        @endforeach

                        <div class="w-full mb-4">
                            <label for="Heading" class="text-sm font-semibold text-black"> Exam Structure
                                Headline (H2 Headline)
                            </label>
                        </div>
                        <div class="w-full mb-4">
                            <input type="text" id="pointSixHeading"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                data-maxlength="200" name="allHeadline_exam_structure"
                                value="{{ $allHeadline?->exam_structure }}">

                        </div>
                        <div class="w-full mb-4">
                            <label for="Heading" class="text-sm font-semibold text-black"> Exam Structure
                                Content
                            </label>
                        </div>
                        <div class="w-full mb-4">
                            <br />
                            <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                name="newExamstructure_exam_structur">{!! $newExamstructure?->exam_structure !!}</textarea>
                        </div>

                    </div>
                </div>
                <div data-content="4" class="tab-pane hidden">
                    <div class="p-2">
                        <div class="w-full">
                            <div class="">
                                <div class="p-4 mb-4 bg-white rounded-lg border border-blue-300 ">
                                    <div class="w-full">
                                        <label for="Heading" class="text-sm font-semibold text-black">Heading (H2
                                            Headline)
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="Heading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                name="heading_eligibility" value="{{ $allHeadline?->eligibility }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    @if ($courseHeadingDescription)
                                        <label for=""
                                            class="text-sm font-semibold mt-4 text-black">{{ __('common.description') }}</label>
                                        <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                            name="eligibility_description">{!! $courseHeadingDescription?->eligibility !!}</textarea>
                                    @endif

                                    <div id="eligibility-list-1" class="mt-4 space-y-3">
                                        <label class="text-sm font-semibold text-black">Bullet
                                            Points </label>
                                        @if (!empty($cerEligibility))
                                            @foreach ($cerEligibility as $item)
                                                <div
                                                    class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md  hover:-translate-y-px">

                                                    <input type="text" name="cerEligibility[]"
                                                        value="{{ $item }}" placeholder="Enter new point"
                                                        class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                                                        aria-label="Eligibility point">
                                                    <button onclick="deleteItem(this)"
                                                        class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                        <i class="ri-close-fill"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No eligibility criteria available.</p>
                                        @endif

                                    </div>
                                    <div class="mt-8 text-end">
                                        <button type="button" onclick="addItem('eligibility-list-1')"
                                            class="btn-primary-sm ">
                                            <i class="ri-add-line mr-2"></i>
                                            Add item
                                        </button>
                                    </div>
                                </div>
                                <div class="">
                                    <h2 class="text-lg font-semibold mb-4 border-b pb-2 text-gray-800">
                                        Course Salary Breakdown</h2>
                                    <div class="space-y-4">
                                        <div class="rounded-lg border border-gray-200">
                                            <h3
                                                class="p-4 text-base bg-gray-100  font-semibold  text-gray-800 mb-5 border-b pb-3 border-gray-200">
                                                Position One</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-4 p-4 lg:grid-cols-5 gap-2">
                                                <div class="md:col-span-2">
                                                    <label for="position_one_name"
                                                        class="text-sm font-semibold text-black">Role
                                                        Name</label>
                                                    <input type="text" id="position_one_name"
                                                        name="position_one_name"
                                                        value="{{ $courseSalary?->position_one_name }}"
                                                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                </div>
                                                <div>
                                                    <label for="position_one_min_salary"
                                                        class="text-sm font-semibold text-black">Min
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_one_min_salary"
                                                            name="position_one_min_salary"
                                                            value="{{ $courseSalaryone['position_one_salary']['min'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="position_one_avg_salary"
                                                        class="text-sm font-semibold text-black">Avg
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_one_avg_salary"
                                                            name="position_one_avg_salary"
                                                            value="{{ $courseSalaryone['position_one_salary']['avg'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>

                                                <div>
                                                    <label for="position_one_max_salary"
                                                        class="text-sm font-semibold text-black">Max
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_one_max_salary"
                                                            name="position_one_max_salary"
                                                            value="{{ $courseSalaryone['position_one_salary']['max'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg border border-gray-200">
                                            <h3
                                                class="text-base  p-4 bg-gray-100 font-semibold text-gray-800 mb-5 border-b pb-3 border-gray-200">
                                                Position Two</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 p-4 gap-4">
                                                <div class="md:col-span-2">
                                                    <label for="position_two_name"
                                                        class="text-sm font-semibold text-black">Role
                                                        Name</label>
                                                    <input type="text" id="position_two_name"
                                                        name="position_two_name"
                                                        value="{{ $courseSalary?->position_two_name }}"
                                                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                </div>
                                                <div>
                                                    <label for="position_two_min_salary"
                                                        class="text-sm font-semibold text-black">Min
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_two_min_salary"
                                                            name="position_two_min_salary"
                                                            value="{{ $courseSalaryone['position_two_salary']['min'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="position_two_avg_salary"
                                                        class="text-sm font-semibold text-black">Avg
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_two_avg_salary"
                                                            name="position_two_avg_salary"
                                                            value="{{ $courseSalaryone['position_two_salary']['avg'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="position_two_max_salary"
                                                        class="text-sm font-semibold text-black">Max
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_two_max_salary"
                                                            name="position_two_max_salary"
                                                            value="{{ $courseSalaryone['position_two_salary']['max'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg border border-gray-200">
                                            <h3
                                                class="text-base p-4 bg-gray-100 font-semibold text-gray-800 mb-5 border-b pb-3 border-gray-200">
                                                Position Three</h3>
                                            <div class="grid grid-cols-1 p-4 md:grid-cols-4 lg:grid-cols-5 gap-6">
                                                <div class="md:col-span-2">
                                                    <label for="position_three_name"
                                                        class="text-sm font-semibold text-black">Role
                                                        Name</label>
                                                    <input type="text" id="position_three_name"
                                                        name="position_three_name"
                                                        value="{{ $courseSalary?->position_three_name }}"
                                                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                </div>
                                                <div>
                                                    <label for="position_three_min_salary"
                                                        class="text-sm font-semibold text-black">Min
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_three_min_salary"
                                                            name="position_three_min_salary"
                                                            value="{{ $courseSalaryone['position_three_salary']['min'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="position_three_avg_salary"
                                                        class="text-sm font-semibold text-black">Avg
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_three_avg_salary"
                                                            name="position_three_avg_salary"
                                                            value="{{ $courseSalaryone['position_three_salary']['avg'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="position_three_max_salary"
                                                        class="text-sm font-semibold text-black">Max
                                                        Salary</label>
                                                    <div class="relative rounded-lg shadow-sm">
                                                        <input type="text" id="position_three_max_salary"
                                                            name="position_three_max_salary"
                                                            value="{{ $courseSalaryone['position_three_salary']['max'] ?? '' }}"
                                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                            placeholder="0">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-content="6" class="tab-pane hidden">
                    <div class="p-2">
                        <div class="w-full">
                            <div class="grid grid-cols-1 sm:grid-cols-1 mb-4 lg:grid-cols-1 gap-4">
                                <div class="w-full">
                                    <label class="text-sm font-semibold text-black">Main Heading (H2 Headline)
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="roadmap_path"
                                            value="{{ $allHeadline?->path }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>

                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="w-full">
                                        <label for="pointOneHeading" class="text-sm font-semibold text-black">Point
                                            One
                                            Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointOneHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_point_one_heading"
                                                value="{{ $courseCertificationData['pointer_one']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointOneDescription"
                                            class="text-sm font-semibold text-black">Point
                                            One Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="pointOneDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_one_description">{{ $courseCertificationData['pointer_one']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="w-full">
                                        <label for="pointTwoHeading" class="text-sm font-semibold text-black">Point
                                            Two
                                            Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointTwoHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_point_two_heading"
                                                value="{{ $courseCertificationData['pointer_two']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointTwoDescription"
                                            class="text-sm font-semibold text-black">Point
                                            Two Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="pointTwoDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_two_description">{{ $courseCertificationData['pointer_two']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="w-full">
                                        <label for="pointThreeHeading" class="text-sm font-semibold text-black">Point
                                            Three Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointThreeHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_point_three_heading"
                                                value="{{ $courseCertificationData['pointer_three']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointThreeDescription"
                                            class="text-sm font-semibold text-black">Point
                                            Three Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="pointThreeDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_three_description">{{ $courseCertificationData['pointer_three']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="w-full">
                                        <label for="pointFourHeading" class="text-sm font-semibold text-black">Point
                                            Four
                                            Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointFourHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_point_four_heading"
                                                value="{{ $courseCertificationData['pointer_four']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointFourDescription"
                                            class="text-sm font-semibold text-black">Point
                                            Four Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="pointFourDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_four_description">{{ $courseCertificationData['pointer_four']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg  bg-white">
                                    <div class="w-full">
                                        <label for="pointFiveHeading" class="text-sm font-semibold text-black">Point
                                            Five
                                            Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointFiveHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_point_five_heading"
                                                value="{{ $courseCertificationData['pointer_five']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointFiveDescription"
                                            class="text-sm font-semibold text-black">Point
                                            Five Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="schedules_pointFiveDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_five_description">{{ $courseCertificationData['pointer_five']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>
                                </div>
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="w-full">
                                        <label for="pointSixHeading" class="text-sm font-semibold text-black">Point
                                            Six
                                            Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointSixHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_point_six_heading"
                                                value="{{ $courseCertificationData['pointer_six']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointSixDescription"
                                            class="text-sm font-semibold text-black">Point
                                            Six Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="pointSixDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_six_description">{{ $courseCertificationData['pointer_six']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>

                                </div>
                                <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                    <div class="w-full">
                                        <label for="pointSixHeading" class="text-sm font-semibold text-black">Point
                                            Seven
                                            Heading
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <input type="text" id="pointSixHeading"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="200" name="schedules_seven_heading"
                                                value="{{ $courseCertificationData['pointer_seven']['heading'] ?? '' }}">
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="pointSixDescription"
                                            class="text-sm font-semibold text-black">Point
                                            Seven Description
                                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                            <textarea type="text" rows="7" id="pointSixDescription"
                                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                data-maxlength="500" name="schedules_point_seven_description">{{ $courseCertificationData['pointer_seven']['text'] ?? '' }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                            </div>
                                    </div>

                                </div>
                            </div>
                            <div class="p-4"></div>
                            <div class="w-full">
                                <label class="text-sm font-semibold text-black"> Trainers Heading (H2 Headline)
                                    {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                    <input type="text"
                                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        data-maxlength="200" name="trainers_heading"
                                        value="{{ $allHeadline?->trainers }}">
                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                    </div>
                            </div>
                            <div class="w-full">
                                <label for="pointSixDescription" class="text-sm font-semibold text-black">Trainers
                                    Description
                                    {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                    <textarea type="text" rows="7" id="pointSixDescription"
                                        class="form-input mt-1 description-validation-summernote summernote" name="trainers_description">{!! $courseHeadingDescription?->trainers !!}</textarea>

                            </div>
                        </div>
                    </div>
                </div>
                <div data-content="7" class="tab-pane hidden">
                    <div class="p-2">

                        <div class="w-full">
                            <label for="" class="text-sm font-semibold text-black">Real Success Stories
                                Headline (H2 Headline)</label>
                            <input type="text" class="form-input mt-1" name="allHeadline_course_benefits"
                                value="{{ $allHeadline?->course_benefits }}">
                        </div>
                        <div class="w-full my-4">

                            <label for="" class="text-sm font-semibold text-black">Real Success Stories
                                Description</label>
                            <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                name="courseBenefits_description">{!! $courseBenefits?->why_course_important_readmore !!}</textarea>

                        </div>

                        <div class="w-full my-4">
                            <label for="" class="text-sm font-semibold text-black">Learning Objective
                                Headline (H2 Headline)</label>
                            <input type="text" class="form-input mt-1" name="old_learning_objectives"
                                value="{{ $allHeadline?->old_learning_objectives }}">
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="w-full">
                                    <label for="pointOneHeading" class="text-sm font-semibold text-black">Point One
                                        Heading
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="recordsObjectives_point_one_heading"
                                            value="{{ $recordsObjectives['record_one']['heading'] ?? null }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="w-full">
                                    <label for="pointOneDescription" class="text-sm font-semibold text-black">Point
                                        One Description
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <textarea type="text" rows="7"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="500" name="recordsObjectives_point_one_description">{{ $recordsObjectives['record_one']['description'] ?? null }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                        </div>
                                </div>
                            </div>
                            <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="w-full">
                                    <label for="pointTwoHeading" class="text-sm font-semibold text-black">Point Two
                                        Heading
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="recordsObjectives_point_two_heading"
                                            value="{{ $recordsObjectives['record_two']['heading'] ?? null }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="w-full">
                                    <label for="pointTwoDescription" class="text-sm font-semibold text-black">Point
                                        Two Description
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <textarea type="text" rows="7"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="500" name="recordsObjectives_point_two_description"> {{ $recordsObjectives['record_two']['description'] ?? null }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                        </div>
                                </div>
                            </div>
                            <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="w-full">
                                    <label for="pointThreeHeading" class="text-sm font-semibold text-black">Point
                                        Three Heading
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text" id="pointThreeHeading"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="recordsObjectives_point_three_heading"
                                            value="{{ $recordsObjectives['record_three']['heading'] ?? null }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="w-full">
                                    <label for="pointThreeDescription"
                                        class="text-sm font-semibold text-black">Point
                                        Three Description
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <textarea type="text" rows="7" id="pointThreeDescription"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="500" name="recordsObjectives_point_three_description">{{ $recordsObjectives['record_three']['description'] ?? null }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                        </div>
                                </div>
                            </div>
                            <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="w-full">
                                    <label for="pointFourHeading" class="text-sm font-semibold text-black">Point
                                        Four
                                        Heading
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text" id="pointFourHeading"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="recordsObjectives_point_four_heading"
                                            value="{{ $recordsObjectives['record_four']['heading'] ?? null }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="w-full">
                                    <label for="pointFourDescription" class="text-sm font-semibold text-black">Point
                                        Four Description
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <textarea type="text" rows="7" id="pointFourDescription"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="500" name="recordsObjectives_point_four_description">{{ $recordsObjectives['record_four']['description'] ?? null }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                        </div>
                                </div>
                            </div>
                            <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg  bg-white">
                                <div class="w-full">
                                    <label for="pointFiveHeading" class="text-sm font-semibold text-black">Point
                                        Five
                                        Heading
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text" id="pointFiveHeading"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="recordsObjectives_point_five_heading"
                                            value="{{ $recordsObjectives['record_five']['heading'] ?? null }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="w-full">
                                    <label for="pointFiveDescription" class="text-sm font-semibold text-black">Point
                                        Five Description
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <textarea type="text" id="pointFiveDescription"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="500" name="recordsObjectives_point_five_description">{{ $recordsObjectives['record_five']['description'] ?? null }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                        </div>
                                </div>
                            </div>
                            <div class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                <div class="w-full">
                                    <label for="pointSixHeading" class="text-sm font-semibold text-black">Point Six
                                        Heading
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <input type="text" id="pointSixHeading"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="200" name="recordsObjectives_point_six_heading"
                                            value="{{ $recordsObjectives['record_six']['heading'] ?? null }}">
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                        </div>
                                </div>
                                <div class="w-full">
                                    <label for="pointSixDescription" class="text-sm font-semibold text-black">Point
                                        Six Description
                                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                        <textarea type="text" rows="7" id="pointSixDescription"
                                            class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            data-maxlength="500" name="recordsObjectives_point_six_description">{{ $recordsObjectives['record_six']['description'] ?? null }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                        </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <hr />
                        <br />
                        <br />
                        <div class="w-full">
                            <label for="" class="text-sm font-semibold text-black">Certifications
                                Headline (H2 Headline)</label>
                            <input type="text" class="form-input mt-1" name="allHeadline_certifications"
                                value="{{ $allHeadline?->certifications }}">
                        </div>
                        <br />
                        <label for="" class="text-sm font-semibold mt-4 text-black">Certifications
                            Description</label>

                        <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                            name="certificatetext_description">{{ $certificatetext?->description }}</textarea>



                        <br />
                    </div>
                </div>
                <div data-content="8" class="tab-pane hidden">
                    <div class="p-2">
                        <div class="w-full my-4">

                            <div class="w-full">
                                <label for="pointOneHeading" class="text-sm font-semibold text-black">
                                    Heading (H2 Headline)
                                    {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                    <input type="text" value="{{ $allHeadline?->testimonials }}"
                                        id="pointOneHeading"
                                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        name="testimonials_headingh">
                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                    </div>

                                    <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                        name="testimonials_description">{!! $courseHeadingDescription?->testimonials !!}</textarea>
                            </div>
                            <br />
                            <div class="w-full">
                                <label for="pointOneHeading" class="text-sm font-semibold text-black">
                                    Heading (H2 Headline)
                                    {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                                    <input type="text" value="{{ $allHeadline?->about_geography }}"
                                        id="about_geography"
                                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        name="aboutgeography">
                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                    </div>


                                    <div class="w-full">


                                        <br />
                                        <textarea type="text" class="form-input mt-1 description-validation-summernote summernote"
                                            name="aboutGeography_description">{!! $aboutGeography?->description !!}</textarea>

                                    </div>

                                    <div class="w-full my-4">
                                        <label for="" class="text-sm font-semibold text-black">Training
                                            Options
                                            Headline (H2 Headline)</label>
                                        <input type="text" class="form-input mt-1" name="training_options"
                                            value="{{ $allHeadline?->training_options }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">Company
                                            Hiring
                                            Headline (H2 Headline)</label>
                                        <input type="text" class="form-input mt-1"
                                            name="allHeadline_hiring_company"
                                            value="{{ $allHeadline?->hiring_company }}">
                                    </div>
                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">Company
                                            Alumni
                                            Headline (H2 Headline)</label>
                                        <input type="text" class="form-input mt-1" name="allHeadline_alumni"
                                            value="{{ $allHeadline?->alumni }}">
                                    </div>
                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">Segment
                                            Headline (H2 Headline)</label>
                                        <input type="text" class="form-input mt-1" name="allHeadline_segment"
                                            value="{{ $allHeadline?->segment }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">
                                            Recommended Course Headline (H2 Headline)</label>
                                        <input type="text" class="form-input mt-1" name="recommended_course"
                                            value="{{ $allHeadline?->recommended_course }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">
                                            Recommended Course Option 1</label>
                                        <input type="text" class="form-input mt-1" name="roc_option1"
                                            value="{{ $allHeadline?->roc_option1 }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">
                                            Recommended Course Option 2</label>
                                        <input type="text" class="form-input mt-1" name="roc_option2"
                                            value="{{ $allHeadline?->roc_option2 }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">
                                            Recommended Course Option 3</label>
                                        <input type="text" class="form-input mt-1" name="roc_option3"
                                            value="{{ $allHeadline?->roc_option3 }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">Segment
                                            Recommended Course Option 4</label>
                                        <input type="text" class="form-input mt-1" name="roc_option4"
                                            value="{{ $allHeadline?->roc_option4 }}">
                                    </div>

                                    <div class="w-full mt-4">
                                        <label for="" class="text-sm font-semibold text-black">Course Schedule Heading
                                            </label>
                                        <input type="text" class="form-input mt-1" name="schedule"
                                            value="{{ $allHeadline?->schedule }}">
                                    </div>



                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </details>

    <div class="mt-4">
        <details
            class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md">
            <summary
                class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
                <span class="text-sm font-semibold">SEO Content</span>
                <i class="ri-arrow-down-s-line text-xl"></i>
            </summary>
            <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-4">

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Meta Title
                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                            <input type="text" id="meta_title"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ $headline?->meta_title }}" data-maxlength="200" name="meta_title">
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Meta Keyword
                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                            <textarea type="text" id="metaKeyword"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                data-maxlength="600" name="meta_keywords">{{ $headline?->meta_keywords }}</textarea>
                            {{-- <input type="text"
                        class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                        value="{{ $headline?->meta_keywords }}" data-maxlength="200" name="metaKeyword"> --}}
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/600</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Alt Text
                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                            <input type="text" id="Heading"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ $headline?->imageAltText }}" data-maxlength="200" name="imageAltText">
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Meta Description
                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                            <textarea type="text"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                data-maxlength="300" name="metaDescription">{{ $headline?->metaDescription ?: $headline?->meta_description }}
</textarea>
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/300</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Alt Description
                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                            <textarea type="text" id="Heading"
                                class="count-validation form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                data-maxlength="300" name="imageAltDescription">{{ $headline?->imageAltDescription }}</textarea>
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/300</div>
                    </div>
                </div>
            </div>
        </details>
    </div>
    <div class="mt-8 text-end">
        <button type="submit" class="btn-primary-sm ">
            Submit</button>
    </div>
    </form>
</div>
@include('Course::Dynamic_pages.modal')
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.tab2_js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@include('layouts.modal_js')
<script>
    document.querySelectorAll("details.accordion").forEach((detail) => {
        detail.addEventListener("toggle", function() {
            if (this.open) {
                document.querySelectorAll("details.accordion").forEach((other) => {
                    if (other !== this) other.removeAttribute("open");
                });
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        function setupImageUploader({
            dropAreaId,
            inputId,
            previewId,
            removeBtnId = null,
            errorId = null
        }) {
            const dropArea = document.getElementById(dropAreaId);
            const inputFile = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const img = preview ? preview.querySelector("img") || preview : null;
            const removeBtn = removeBtnId ? document.getElementById(removeBtnId) : null;
            const errorEl = errorId ? document.getElementById(errorId) : null;

            if (!dropArea || !inputFile) return;
            dropArea.addEventListener("click", () => inputFile.click());
            inputFile.addEventListener("change", (e) => handleFile(e.target.files[0]));
            dropArea.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropArea.classList.add("border-blue-500", "bg-blue-50");
            });
            dropArea.addEventListener("dragleave", () => {
                dropArea.classList.remove("border-blue-500", "bg-blue-50");
            });
            dropArea.addEventListener("drop", (e) => {
                e.preventDefault();
                dropArea.classList.remove("border-blue-500", "bg-blue-50");
                const file = e.dataTransfer.files[0];
                handleFile(file);
            });

            function handleFile(file) {
                if (!file) return;

                const validTypes = ["image/jpeg", "image/png", "image/jpg"];
                if (!validTypes.includes(file.type)) {
                    if (errorEl) {
                        errorEl.textContent = "Only JPG, JPEG, or PNG images are allowed!";
                        errorEl.classList.remove("hidden");
                    } else {
                        alert("Only JPG, JPEG, or PNG images are allowed!");
                    }
                    inputFile.value = "";
                    return;
                }

                if (errorEl) errorEl.classList.add("hidden");

                const reader = new FileReader();
                reader.onload = (e) => {
                    if (img) {
                        if (img.tagName === "IMG") {
                            img.src = e.target.result;
                            img.classList.remove("hidden");
                        } else {
                            img.innerHTML =
                                `<img src="${e.target.result}" class="mx-auto max-h-48 rounded-lg shadow">`;
                        }
                    }
                    if (preview) preview.classList.remove("hidden");
                };
                reader.readAsDataURL(file);
            }
            if (removeBtn) {
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    if (img && img.tagName === "IMG") img.src = "";
                    inputFile.value = "";
                    if (preview) preview.classList.add("hidden");
                });
            }
        }
        setupImageUploader({
            dropAreaId: "image-drop-area",
            inputId: "image-input",
            previewId: "image-preview",
            removeBtnId: "remove-image-btn"
        });

        // setupImageUploader({
        //     dropAreaId: "feature_image",
        //     inputId: "feature_image",
        //     previewId: "feature_image_preview",
        //     errorId: "feature-image-error"
        // });
        setupImageUploader({
            dropAreaId: "testimonial_image_drop",
            inputId: "testimonial_image",
            previewId: "testimonial_image_preview",
            removeBtnId: "testimonial_remove_image"
        });
    });
</script>
<script>
    function deleteItem(button) {
        const parentDiv = button.closest('div');
        if (parentDiv) {
            parentDiv.remove();
        }
    }

    function addItem(listId) {
        const list = document.getElementById(listId);
        if (!list) {
            console.error('List element not found with ID:', listId);
            return;
        }

        const newItemHtml = `
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:border-gray-300 hover:-translate-y-px mt-2">
            <input type="text" value="" name="cerEligibility[]" placeholder="Enter new point" class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none" aria-label="Eligibility point">
            <button onclick="deleteItem(this)" class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                <i class="ri-close-fill"></i>
            </button>
        </div>
    `;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newItemHtml.trim();
        const newItem = tempDiv.firstChild;
        list.appendChild(newItem);
        newItem.querySelector('input[type="text"]').focus();
    }
</script>

<script>
    function deleteItem_whatweLearn(button) {
        const parentDiv = button.closest('div');
        if (parentDiv) {
            parentDiv.remove();
        }
    }

    function addItemwhatweLearn(listId) {
        const list = document.getElementById(listId);
        if (!list) {
            console.error('List element not found with ID:', listId);
            return;
        }

        const newItemHtml = `
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:border-gray-300 hover:-translate-y-px mb-2 mp-2">
            <input type="text" value="" name="pointers[]" placeholder="Enter new pointers" class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none" aria-label="Eligibility point">
            <button onclick="deleteItem(this)" class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                <i class="ri-close-fill"></i>
            </button>
        </div>
    `;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newItemHtml.trim();
        const newItem = tempDiv.firstChild;
        list.appendChild(newItem);
        newItem.querySelector('input[type="text"]').focus();
    }
</script>
<script>
    $(document).ready(function() {
        let selectedState = "{{ old('state_id', $data?->state_id) }}";

        function loadStates(countryId, selectedId = null) {
            if (!countryId) return;

            $.ajax({
                url: "{{ url('get-child-stateName') }}", // Adjust if route needs ID
                type: "GET",
                data: {
                    parent_id: countryId
                },
                success: function(res) {
                    let data = res.data ?? res;
                    let $stateSelect = $("#state_id");

                    $stateSelect.empty().append('<option value="">Select State</option>');

                    $.each(data, function(key, state) {
                        let selected = (selectedId && selectedId == state.id) ? 'selected' :
                            '';
                        $stateSelect.append(
                            `<option value="${state.id}" ${selected}>${state.name}</option>`
                        );
                    });
                },
                error: function(xhr) {
                    console.error("Error loading states:", xhr.responseText);
                }
            });
        }

        // On country change
        $("#country_id").change(function() {
            loadStates($(this).val());
        });

        // Auto-load when editing
        let initialCountry = $("#country_id").val();
        if (initialCountry) {
            loadStates(initialCountry, selectedState);
        }
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
                // input.value = '';
                return;
            }

            if (file.size > maxSize) {
                error.textContent = `File "${file.name}" exceeds the ${maxSizeMB}MB limit.`;
                error.classList.remove('hidden');
                // input.value = '';
                return;
            }
        });
    }

    // Apply validation
    // validateFileInput('feature_image', 'feature-image-error', ['image/jpeg', 'image/png'], 2);
    validateFileInput('brochure_file', 'brochure-error', ['application/pdf'], 2);
</script>

<script>
    // Tagline character counter
    const tagline = document.getElementById('tagline');
    const taglineCount = document.getElementById('tagline-count');
    tagline.addEventListener('input', () => {
        const len = tagline.value.length;
        const max = tagline.dataset.maxlength || 200;
        taglineCount.textContent = `${len} / ${max}`;
    });

    // Bullet Points logic
    const bulletPointsContainer = document.getElementById('bulletPoints');
    const addBtn = document.getElementById('addBtn');

    function createBulletPoint(value = '') {
        const wrapper = document.createElement('div');
        wrapper.className = "flex items-center gap-2";

        const input = document.createElement('input');
        input.type = 'text';
        input.value = value;
        input.placeholder = "Enter bullet point";
        input.className =
            "flex-1 rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring focus:ring-blue-200 focus:border-blue-500 transition";

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.innerHTML = '<i class="ri-close-line"></i>';
        removeBtn.className = "text-red-500 hover:text-red-700 transition";
        removeBtn.addEventListener('click', () => wrapper.remove());

        wrapper.appendChild(input);
        wrapper.appendChild(removeBtn);
        bulletPointsContainer.appendChild(wrapper);
    }

    addBtn.addEventListener('click', () => {
        createBulletPoint();
    });
</script>

{{-- <script>
    // Character counter for Course Name
    const courseName = document.getElementById('course_name');
    const charCount = document.getElementById('course-name-count');
    courseName.addEventListener('input', () => {
        const len = courseName.value.length;
        const max = courseName.dataset.maxlength || 100;
        charCount.textContent = `${len} / ${max}`;
    });

    // Image preview for Feature Image with remove option
    // const featureInput = document.getElementById('feature_image');
    // const preview = document.getElementById('feature_image_preview');

    // Create remove button dynamically
    // const removeBtn = document.createElement('button');
    // removeBtn.type = 'button';
    // removeBtn.textContent = 'Remove';
    // removeBtn.className = 'mt-2 px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition';
    // removeBtn.style.display = 'none'; // hide initially
    // preview.insertAdjacentElement('afterend', removeBtn);

    // removeBtn.addEventListener('click', () => {
    //     featureInput.value = ''; // reset file input
    //     preview.src = '';
    //     preview.classList.add('hidden');
    //     removeBtn.style.display = 'none';
    // });

    featureInput.addEventListener('change', () => {
        const file = featureInput.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            removeBtn.style.display = 'inline-block';
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            removeBtn.style.display = 'none';
        }
    });
</script> --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const dropArea = document.getElementById("feature-image-drop-area");
        const input = document.getElementById("feature_image");
        const previewImg = document.getElementById("preview-img");
        const previewBox = document.getElementById("feature_image_preview");
        const uploadText = document.getElementById("upload-text");
        const removeBtn = document.getElementById("remove-image");

        let droppedFile = null; // 🔥 IMPORTANT

        // 🛑 Prevent browser default behavior
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
            dropArea.addEventListener(event, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropArea.addEventListener("click", () => input.click());

        dropArea.addEventListener("drop", e => {
            const file = e.dataTransfer.files[0];
            if (!file) return;

            droppedFile = file; // store reference
            attachFileToInput(file);
            showPreview(file);
        });

        input.addEventListener("change", () => {
            if (input.files.length) {
                droppedFile = input.files[0];
                showPreview(droppedFile);
            }
        });

        function attachFileToInput(file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
        }

        function showPreview(file) {
            previewImg.src = URL.createObjectURL(file);
            previewBox.classList.remove("hidden");
            uploadText.classList.add("hidden");
            removeBtn.classList.remove("hidden");
        }

        removeBtn.addEventListener("click", () => {
            droppedFile = null;
            input.value = "";
            previewImg.src = "";
            previewBox.classList.add("hidden");
            uploadText.classList.remove("hidden");
            removeBtn.classList.add("hidden");
        });

        // 🔥 FINAL GUARANTEE BEFORE SUBMIT
        const form = input.closest("form");
        if (form) {
            form.addEventListener("submit", () => {
                if (droppedFile && input.files.length === 0) {
                    attachFileToInput(droppedFile);
                }
            });
        }
    });
</script>
@endsection
