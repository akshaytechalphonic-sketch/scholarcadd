@extends('layouts.app')
@section('css')
@include('layouts.select2css')
@include('layouts.summer_note_css')
@endsection
@section('content')
<div>
    @php echo breadcrums('Course.Course Add', ['common.home', 'Course.Course List', 'Course.Course Add'], ['dashboard',
    'bank-list']) @endphp
    @section('back_button')
    @php echo back_button('back') @endphp
    @endsection
    <div class="mt-4">
    <details class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md">
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Course Information</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
            <div>
                <label for="course_name" class="text-sm font-semibold text-black">
                    Course Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="course_name" value="{{ $data?->name }}" required
                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                    data-maxlength="100" />
                <p id="course-name-count" class="mt-1 text-xs text-gray-400 text-right">0 / 100</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-4">
                <div>
                    <label for="category_id" class="text-sm font-semibold text-black">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Category</option>
                        @foreach ($course as $courses)
                        <option value="{{ $courses->id }}" @if (old('category_id', $data?->course_id) == $courses->id)
                            selected @endif>{{ $courses->course_title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="subcategory_id" class="text-sm font-semibold text-black">
                        Sub Category
                    </label>
                    <select name="subcategory_id" id="subcategory_id"
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Sub Category</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="text-sm font-semibold text-black">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="Active" @if ($data?->status == 'Active' || !$data) selected @endif>Active
                        </option>
                        <option value="In active" @if ($data?->status == 'In active') selected @endif>In active
                        </option>
                        <option value="Discontinued" @if ($data?->status == 'Discontinued') selected @endif>
                            Discontinued</option>
                    </select>
                </div>
            </div>
            <div class="relative mt-4">
                <label class="text-sm font-semibold text-black">Feature
                    Image</label>
                <div
                    class="flex items-center justify-center w-full h-32 sm:h-36 border-2 border-dashed border-gray-400 rounded-xl bg-gray-50 cursor-pointer hover:border-blue-500 hover:bg-gray-100 transition relative overflow-hidden">
                    <input type="file" name="feature_image" id="feature_image" accept=".jpg,.jpeg,.png"
                        class="absolute w-full h-full opacity-0 cursor-pointer">
                    <span class="text-gray-400 text-sm sm:text-base">Drag & drop or click to upload</span>
                </div>
                <p id="feature-image-error" class="text-xs text-red-500 mt-1 hidden"></p>
                <div class="mt-2">
                    <img id="feature_image_preview" class="hidden h-20 sm:h-24 rounded-md border object-contain" src=""
                        alt="Preview">
                </div>
            </div>
            @if ($data?->Course_image)
            <div>
                <label class="text-sm font-semibold text-black">Uploaded
                    Image</label>
                <div class="p-3 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center">
                    <a target="_blank" href="{{ route('secure-documents', ['path' => $data?->Course_image]) }}">
                        <img src="{{ route('secure-documents', ['path' => $data?->Course_image]) }}"
                            class="h-20 sm:h-24 object-contain rounded-md" alt="Uploaded Image">
                    </a>
                </div>
            </div>
            @endif
            <div class="mt-4">
                <label for="tagline" class="text-sm font-semibold text-black">Tagline</label>
                <textarea id="tagline" placeholder="Enter tagline"
                    class="description-validation form-input mt-1 w-full border border-gray-300 rounded-lg shadow-sm p-2 text-gray-700 focus:ring-0 focus:outline-none"
                    data-maxlength="200" rows="2"></textarea>
                <div id="tagline-count" class="char-count text-xs text-gray-500 text-right mt-1">0 / 200</div>
            </div>
            <div>
                <label class="text-sm font-semibold text-black">Bullet Points</label>
                <div id="bulletPoints" class="space-y-3">
                    <div
                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                        <input type="text" value="" placeholder="Enter bullet point"
                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                            aria-label="Bullet point">
                        <button onclick="deleteItem(this)"
                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <i class="ri-close-fill"></i>
                        </button>
                    </div>
                    <div
                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                        <input type="text" value="" placeholder="Enter bullet point"
                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                            aria-label="Bullet point">
                        <button onclick="deleteItem(this)"
                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <i class="ri-close-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-8 text-end">
                    <button onclick="addItem('bulletPoints')" class="btn-primary-sm ">
                        <i class="ri-add-line mr-2"></i>Add item</button>
                </div>
            </div>
        </div>
        </details>
    </div>

    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
        <form action="{{ route('course-update') }}" method="post" enctype="multipart/form-data">
            @else
            <form action="{{ route('course-store') }}" method="post" enctype="multipart/form-data">
                @endif
                @csrf
                @if ($data)
                <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                    data-maxlength="100" name="Course_id" required>
                @endif
                <!-- <div class="grid grid-cols-3 md:grid-cols-3 gap-5 w-full my-2">
                    <div class="w-full">
                        <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="limited-input form-input mt-1" value="{{ $data?->name }}"
                            data-maxlength="100" name="name" required></input>
                        <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">{{ __('Course.Category') }}
                            <span class="text-red-500 font-bold">*</span></label>
                        <select id="category_id" class="select-single mt-1" style="width:100%" name="category_id" required>
                            <option value="" disabled>Select Category..</option>
                            @foreach ($course as $courses)
                            <option value="{{ $courses->id }}" @if (old('category_id', $data?->course_id) == $courses->id) selected @endif>{{ $courses->course_title }}</option>@endforeach
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">{{ __('Course.Sub Category') }}</label>
                        <select id="subcategory_id" class="select-single mt-1" style="width:100%" name="subcategory_id">
                            <option value="" disabled>Select Sub Category..</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">{{ __('Course.Warehouse') }}</label>
                        <select id="warehouse_id" class="select-single mt-1" style="width:100%" name="warehouse_id">
                            <option value="" disabled>Select Warehouse..</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Status') }} <span
                            class="text-red-500 font-bold">*</span></label>
                        <select class="select-single mt-1" style="width:100%" name="status" required>
                            <option value="Active" @if ($data?->status == 'Active' || !$data) selected @endif>Active </option>
                            <option value="In active" @if ($data?->status == 'In active') selected @endif>In active </option>
                            <option value="Discontinued" @if ($data?->status == 'Discontinued') selected @endif>Discontinued
                            </option>
                        </select>
                    </div>
                    {{-- ///////// Feature Image Upload --}}
                    <div class="w-full">
                        <label for="feature_image" class="text-sm font-semibold text-black">{{ __('Course.Feature Image') }}</label>
                        <input id="feature_image" type="file" class="form-input mt-1" name="feature_image" accept=".jpg, .jpeg, .png">
                        <p id="feature-image-error" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>
                    @if ($data?->Course_image)
                    <div class="w-full ">
                        <label for="Course_image" class="text-sm font-semibold text-black mb-2"> Uploded Image</label>
                        <div class=" p-2 rounded-md border">
                            <a target="_blank" href="{{ route('secure-documents', ['path' => $data?->Course_image]) }}"><img class="h-[50px] " src="{{ route('secure-documents', ['path' => $data?->Course_image]) }}" alt=""></a>
                        </div>
                    </div>
                    @endif

                </div>-->


                <!-- <div class="mt-4">
                        <h2 class="text-lg font-semibold mb-6 border-b pb-2">Banner Section</h2>
                        <div>
                            <label class="block font-semibold mb-2">Tagline</label>
                            <textarea placeholder="Enter tagline" class="limited-input form-input mt-1" data-maxlength="200" rows="2"></textarea>
                            <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/200</div>
                        </div>
                        <div>
                            <label class="block font-semibold mb-2">Bullet Points</label>
                            <div id="bulletPoints" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                            <div class="flex justify-end items-center">
                                <button type="button" id="addBtn" class="btn-primary-sm flex items-center gap-1 mt-4">
                                    <i class="ri-add-line"></i> Add Item
                                </button>
                            </div>
                        </div>
                    </div> -->
                <div class="tab-wrapper flex-1 mt-4 rounded-xl">
                    <div class="tabs mb-8">
                        <div class="flex flex-wrap  bg-gray-100 shadow-sm rounded-t-lg overflow-hidden">
                            <button type="button" data-tab="1" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Course Highlights') }}
                            </button>
                            <button type="button" data-tab="2" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Why Scholaracad') }}
                            </button>
                            <button type="button" data-tab="3" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Curriculum') }}
                            </button>
                            <button type="button" data-tab="4" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Eligibility') }}
                            </button>
                            <button type="button" data-tab="5" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Training Options') }}
                            </button>
                            <button type="button" data-tab="6" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Schedules') }}
                            </button>
                            <button type="button" data-tab="7" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Certificate') }}
                            </button>
                            <button type="button" data-tab="8" class="tab-btn flex-none text-center py-3 px-6 text-base font-inter
                           font-semibold border-b-2 border-[#1D70B7] text-[#1D70B7]
                           hover:bg-blue-50 hover:text-[#1D70B7]
                           focus:outline-none focus:ring-opacity-50
                           transition-all duration-200 ease-in-out">
                                {{ __('Course.Course Review') }}
                            </button>

                        </div>
                    </div>
                    <div class=" ">
                        <div class="tab-content  rounded-sm  ">
                            <div data-content="1" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full mt-4">

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-4">
                                            <div class="w-full">
                                                <label for="pointOne" class="text-sm font-semibold text-black">Point One
                                                    <span class="text-red-500 font-bold">*</span></label>
                                                <input type="text" id="pointOne"
                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="100" name="point_one" required>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                                </div>
                                            </div>

                                            <div class="w-full">
                                                <label for="pointTwo" class="text-sm font-semibold text-black">Point Two
                                                    <span class="text-red-500 font-bold">*</span></label>
                                                <input type="text" id="pointTwo"
                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="100" name="point_two" required>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                                </div>
                                            </div>

                                            <div class="w-full">
                                                <label for="pointThree" class="text-sm font-semibold text-black">Point
                                                    Three
                                                    <span class="text-red-500 font-bold">*</span></label>
                                                <input type="text" id="pointThree"
                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="100" name="point_three" required>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                                </div>
                                            </div>

                                            <div class="w-full">
                                                <label for="pointFour" class="text-sm font-semibold text-black">Point
                                                    Four
                                                    <span class="text-red-500 font-bold">*</span></label>
                                                <input type="text" id="pointFour"
                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="100" name="point_four" required>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                                </div>
                                            </div>

                                            <div class="w-full">
                                                <label for="pointFive" class="text-sm font-semibold text-black">Point
                                                    Five
                                                    <span class="text-red-500 font-bold">*</span></label>
                                                <input type="text" id="pointFive"
                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="100" name="point_five" required>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                                </div>
                                            </div>
                                            <div class="w-full">
                                                <label for="pointSix" class="text-sm font-semibold text-black">Point Six
                                                    <span class="text-red-500 font-bold">*</span></label>
                                                <input type="text" id="pointSix"
                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                    data-maxlength="100" name="point_six" required>
                                                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div data-content="2" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full mt-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointOneHeading"
                                                        class="text-sm font-semibold text-black">Point One Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointOneHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_one_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointOneDescription"
                                                        class="text-sm font-semibold text-black">Point One Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointOneDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_one_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointTwoHeading"
                                                        class="text-sm font-semibold text-black">Point Two Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointTwoHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_two_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointTwoDescription"
                                                        class="text-sm font-semibold text-black">Point Two Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointTwoDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_two_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointThreeHeading"
                                                        class="text-sm font-semibold text-black">Point Three Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointThreeHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_three_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointThreeDescription"
                                                        class="text-sm font-semibold text-black">Point Three Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointThreeDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_three_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointFourHeading"
                                                        class="text-sm font-semibold text-black">Point Four Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointFourHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_four_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointFourDescription"
                                                        class="text-sm font-semibold text-black">Point Four Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointFourDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_four_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg  bg-white">
                                                <div class="w-full">
                                                    <label for="pointFiveHeading"
                                                        class="text-sm font-semibold text-black">Point Five Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointFiveHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_five_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointFiveDescription"
                                                        class="text-sm font-semibold text-black">Point Five Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointFiveDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_five_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointSixHeading"
                                                        class="text-sm font-semibold text-black">Point Six Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointSixHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_six_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointSixDescription"
                                                        class="text-sm font-semibold text-black">Point Six Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointSixDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_six_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-content="3" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full mt-4">
                                        <!-- <label for="" class="text-sm font-semibold text-black">{{
                                            __('Course.Curriculum') }}
                                        </label>
                                        <textarea type="text"
                                            class="form-input mt-1 description-validation-summernote summernote"
                                            name="additional_information">{!! $data?->additional_information !!}</textarea> -->
                                    <div class="flex justify-between items-center mb-3">
                                       <h2 class="text-lg font-semibold">Curriculam List</h2>
                                            <button type="button" onclick="openNewModal('add-curriculam')" class="btn-primary-sm py-1">
                                                {{ __('common.add') }}
                                            </button>
                                    </div>
                                    <table id="data-table-list" class="display table-auto w-full py-1 text-xs">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="px-4 py-2 whitespace-nowrap">Title</th>
                                                <th class="px-4 py-2 whitespace-nowrap">Description</th>
                                                <th class="px-4 py-2 whitespace-nowrap">{{ __('Course.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <div data-content="4" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full">
                                        <div class="">
                                            <div
                                                class="p-4 mb-4 bg-white rounded-lg border border-blue-300 ">
                                                <h2 class="text-xl font-semibold mb-4 pb-2 text-black">
                                                    Prerequisites and Eligibility
                                                </h2>
                                                <div id="eligibility-list-1" class="space-y-3">
                                                    <div
                                                        class="flex items-center border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md  hover:-translate-y-px">
                                                        <input type="text" value="" placeholder="Enter new point"
                                                            class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none"
                                                            aria-label="Eligibility point">
                                                        <button onclick="deleteItem(this)"
                                                            class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                            <i class="ri-close-fill"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mt-8 text-end">
                                                    <button onclick="addItem('eligibility-list-1')"
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
                                                        <div
                                                            class="grid grid-cols-1 md:grid-cols-4 p-4 lg:grid-cols-5 gap-2">
                                                            <div class="md:col-span-2">
                                                                <label for="position_one_name"
                                                                    class="text-sm font-semibold text-black">Role
                                                                    Name</label>
                                                                <input type="text" id="position_one_name"
                                                                    name="position_one_name" value="Devops Engineer"
                                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                            </div>
                                                            <div>
                                                                <label for="position_one_min_salary"
                                                                    class="text-sm font-semibold text-black">Min
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_one_min_salary"
                                                                        name="position_one_min_salary" value="65"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="position_one_avg_salary"
                                                                    class="text-sm font-semibold text-black">Avg
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_one_avg_salary"
                                                                        name="position_one_avg_salary" value="85"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="position_one_max_salary"
                                                                    class="text-sm font-semibold text-black">Max
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_one_max_salary"
                                                                        name="position_one_max_salary" value="95"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-white rounded-lg border border-gray-200">
                                                        <h3
                                                            class="text-base  p-4 bg-gray-100 font-semibold text-gray-800 mb-5 border-b pb-3 border-gray-200">
                                                            Position Two</h3>
                                                        <div
                                                            class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 p-4 gap-4">
                                                            <div class="md:col-span-2">
                                                                <label for="position_two_name"
                                                                    class="text-sm font-semibold text-black">Role
                                                                    Name</label>
                                                                <input type="text" id="position_two_name"
                                                                    name="position_two_name" value="DevOps Lead"
                                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                            </div>
                                                            <div>
                                                                <label for="position_two_min_salary"
                                                                    class="text-sm font-semibold text-black">Min
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_two_min_salary"
                                                                        name="position_two_min_salary" value="65"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="position_two_avg_salary"
                                                                    class="text-sm font-semibold text-black">Avg
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_two_avg_salary"
                                                                        name="position_two_avg_salary" value="85"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="position_two_max_salary"
                                                                    class="text-sm font-semibold text-black">Max
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_two_max_salary"
                                                                        name="position_two_max_salary" value="95"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-white rounded-lg border border-gray-200">
                                                        <h3
                                                            class="text-base p-4 bg-gray-100 font-semibold text-gray-800 mb-5 border-b pb-3 border-gray-200">
                                                            Position Three</h3>
                                                        <div
                                                            class="grid grid-cols-1 p-4 md:grid-cols-4 lg:grid-cols-5 gap-6">
                                                            <div class="md:col-span-2">
                                                                <label for="position_three_name"
                                                                    class="text-sm font-semibold text-black">Role
                                                                    Name</label>
                                                                <input type="text" id="position_three_name"
                                                                    name="position_three_name" value="DevOps Manager"
                                                                    class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                            </div>
                                                            <div>
                                                                <label for="position_two_min_salary"
                                                                    class="text-sm font-semibold text-black">Min
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_two_min_salary"
                                                                        name="position_two_min_salary" value="65"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="position_three_avg_salary"
                                                                    class="text-sm font-semibold text-black">Avg
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_three_avg_salary"
                                                                        name="position_three_avg_salary" value="85"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="position_two_max_salary"
                                                                    class="text-sm font-semibold text-black">Max
                                                                    Salary</label>
                                                                <div class="relative rounded-lg shadow-sm">
                                                                    <input type="text" id="position_two_max_salary"
                                                                        name="position_two_max_salary" value="95"
                                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                                        placeholder="0">
                                                                    <div
                                                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                                        <span
                                                                            class="text-gray-500 sm:text-base">K</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-10 text-end">
                                                    <button type="button" class="btn-primary-sm">
                                                        Add Position
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-content="5" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full flex mt-4 space-x-4">
                                        <div
                                            class="w-1/2 mx-auto p-4 bg-white shadow-lg rounded-lg border border-blue-300">
                                            <h2 class="text-xl font-semibold mb-4 text-black">Online Training</h2>
                                            <div id="online-training-requirements" class="space-y-2">
                                                <div
                                                    class="flex items-center  border rounded-lg p-1 transition-all duration-200 ease-in-out hover:shadow-md hover:-translate-y-px">
                                                    <input type="text" value="" placeholder="Enter new point"
                                                        class="flex-grow bg-transparent border-none text-gray-700 text-base focus:ring-0 focus:outline-none"
                                                        aria-label="Requirement point">
                                                    <button onclick="deleteItem(this)"
                                                        class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl  font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                        <i class="ri-close-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button onclick="addItem('online-training-requirements')"
                                                class="mt-4 w-full bg-[#4B98D6] text-white py-2 px-4 rounded-md hover:bg-[#448eca] transition-colors duration-200">
                                                Add Item
                                            </button>
                                        </div>

                                        <div
                                            class="w-1/2 mx-auto p-4 bg-white shadow-lg rounded-lg border border-[#2E318D]">
                                            <h2 class="text-xl font-semibold mb-4 text-black">Business Group Training
                                            </h2>
                                            <div id="business-group-training-requirements" class="space-y-2">
                                                <div
                                                    class="flex items-center rounded-lg p-1 border transition-all duration-200 ease-in-out hover:shadow-md  hover:-translate-y-px">
                                                    <input type="text" value="" placeholder="Enter new point"
                                                        class="flex-grow bg-transparent border-none text-gray-700 text-base focus:ring-0 focus:outline-none"
                                                        aria-label="Requirement point">
                                                    <button onclick="deleteItem(this)"
                                                        class="ml-4 p-2 bg-red-500 text-white rounded-full w-9 h-9 flex justify-center items-center text-xl font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                        <i class="ri-close-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button onclick="addItem('business-group-training-requirements')"
                                                class="mt-4 w-full bg-[#2E318D] text-white py-2 px-4 rounded-md hover:bg-[#242679] transition-colors duration-200">
                                                Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-content="6" class="tab-pane hidden">
                                <div class="p-2">
                                    <div class="w-full mt-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointOneHeading"
                                                        class="text-sm font-semibold text-black">Point One Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointOneHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_one_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointOneDescription"
                                                        class="text-sm font-semibold text-black">Point One Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointOneDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_one_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointTwoHeading"
                                                        class="text-sm font-semibold text-black">Point Two Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointTwoHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_two_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointTwoDescription"
                                                        class="text-sm font-semibold text-black">Point Two Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointTwoDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_two_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointThreeHeading"
                                                        class="text-sm font-semibold text-black">Point Three Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointThreeHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_three_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointThreeDescription"
                                                        class="text-sm font-semibold text-black">Point Three Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointThreeDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_three_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointFourHeading"
                                                        class="text-sm font-semibold text-black">Point Four Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointFourHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_four_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointFourDescription"
                                                        class="text-sm font-semibold text-black">Point Four Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointFourDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_four_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg  bg-white">
                                                <div class="w-full">
                                                    <label for="pointFiveHeading"
                                                        class="text-sm font-semibold text-black">Point Five Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointFiveHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_five_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointFiveDescription"
                                                        class="text-sm font-semibold text-black">Point Five Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointFiveDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_five_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 flex flex-col p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="w-full">
                                                    <label for="pointSixHeading"
                                                        class="text-sm font-semibold text-black">Point Six Heading
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <input type="text" id="pointSixHeading"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="200" name="point_six_heading" required>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                                                    </div>
                                                </div>
                                                <div class="w-full">
                                                    <label for="pointSixDescription"
                                                        class="text-sm font-semibold text-black">Point Six Description
                                                        <span class="text-red-500 font-bold">*</span></label>
                                                    <textarea type="text" id="pointSixDescription"
                                                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                                        data-maxlength="500" name="point_six_description"
                                                        required></textarea>
                                                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-content="7" class="tab-pane hidden">
                                <div class="p-2">

                                    <div class="w-full">
                                        <label for="" class="text-sm font-semibold text-black">Title</label>
                                        <input type="text" class="form-input mt-1" name="">
                                    </div>
                                    <div class="w-full my-4">
                                        <label for="" class="text-sm font-semibold text-black">Upload Image</label>
                                            <div id="image-drop-area"
                                                class="mt-2 border-2 border-dashed border-gray-400 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition">
                                                <p class="text-gray-500">Drag & Drop your image here or click to upload</p>
                                                <input type="file" id="image-input" accept=".jpg,.jpeg,.png" class="hidden">
                                                <div id="image-preview" class="mt-4 hidden relative inline-block">
                                                    <img src="" alt="Preview" class="mx-auto max-h-48 rounded-lg shadow">
                                                    <button id="remove-image-btn" type="button"
                                                        class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700 transition">
                                                       <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                    <label for="" class="text-sm font-semibold mt-4 text-black">Description</label>

                                     <textarea type="text"
                                            class="form-input mt-1 description-validation-summernote summernote"
                                            name="certificate-description"></textarea>
                                </div>
                            </div>
                            <div data-content="8" class="tab-pane hidden">
                               <div class="p-2">
                                    <div class="grid grid-cols-1 gap-4 p-4 overflow-hidden">
                                        <div class="w-full">
                                          <label class="text-sm font-semibold text-black">
                                            Testimonial Name <span class="text-red-500">*</span>
                                          </label>
                                          <input
                                            type="text"
                                            class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                                            placeholder="Enter name"
                                          >
                                        </div>
                                        <div class="w-full">
                                          <label class="text-sm font-semibold text-black">
                                            Description <span class="text-red-500">*</span>
                                          </label>
                                          <textarea
                                            class="form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2 h-24"
                                            placeholder="Enter testimonial description"
                                          ></textarea>
                                        </div>
                                        <div class="w-full">
                                          <label class="text-sm font-semibold text-black">Upload Image</label>
                                          <div
                                            id="testimonial_image_drop"
                                            class="mt-2 border-2 border-dashed border-gray-400 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition relative overflow-hidden"
                                          >
                                            <p class="text-gray-500 text-sm">
                                              Drag & Drop your image here or click to upload
                                            </p>
                                            <input
                                              type="file"
                                              id="testimonial_image"
                                              name="testimonial_image"
                                              accept="image/*"
                                              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            >
                                          </div>
                                            <div id="testimonial_image_preview" class="mt-3 hidden">
                                              <img
                                                src=""
                                                alt="Preview"
                                                class="w-24 h-24 object-cover rounded-md border border-gray-300"
                                              >
                                              <button
                                                type="button"
                                                id="testimonial_remove_image"
                                                class="text-xs text-red-500 hover:underline ml-2"
                                              >
                                                Remove
                                              </button>
                                            </div>
                                        </div>
                                      </div>
                                    </div>

                            </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="flex justify-end mt-4">
    <div class="flex gap-2">
        <!-- <button type="reset" class="btn-primary-sm flex items-center gap-1">
            <i class="ri-refresh-line"></i>
            {{ __('common.reset') }}
        </button> -->
        <button type="submit" class="btn-success-sm flex items-center gap-1">
            <i class="ri-check-line"></i>
            {{ __('common.submit') }}
        </button>
    </div>
    </div>
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

        // Handle click upload
        dropArea.addEventListener("click", () => inputFile.click());

        // Handle file input change
        inputFile.addEventListener("change", (e) => handleFile(e.target.files[0]));

        // Drag-and-drop support
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

        // Handle file preview + validation
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
                        img.innerHTML = `<img src="${e.target.result}" class="mx-auto max-h-48 rounded-lg shadow">`;
                    }
                }
                if (preview) preview.classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        }

        // Remove image
        if (removeBtn) {
            removeBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                if (img && img.tagName === "IMG") img.src = "";
                inputFile.value = "";
                if (preview) preview.classList.add("hidden");
            });
        }
    }

    // ✅ Initialize multiple uploaders (add as many as you want)
    setupImageUploader({
        dropAreaId: "image-drop-area",
        inputId: "image-input",
        previewId: "image-preview",
        removeBtnId: "remove-image-btn"
    });

    setupImageUploader({
        dropAreaId: "feature_image",
        inputId: "feature_image",
        previewId: "feature_image_preview",
        errorId: "feature-image-error"
    });

    // ✅ Testimonial Image Upload
    setupImageUploader({
        dropAreaId: "testimonial_image_drop",
        inputId: "testimonial_image",
        previewId: "testimonial_image_preview",
        removeBtnId: "testimonial_remove_image"
    });
});
</script>

<script>
    // eligibility_manager.js
    // function deleteItem(button) {
    //     button.closest('.flex').remove();
    // }

    function deleteItem(button) {
        // Find the closest parent div and remove it
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
            <input type="text" value="" placeholder="Enter new  point" class="flex-grow border-none bg-transparent text-gray-700 text-base focus:ring-0 focus:outline-none" aria-label="Eligibility point">
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
    $(document).ready(function () {
        let selectedSubcategory = "{{ old('subcategory_id', $data?->subcategory_id) }}";

        function loadSubcategories(categoryId, selectedId = null) {
            if (!categoryId) return;
            $.ajax({
                url: "{{ route('get-child-Course', '') }}",
                type: "GET",
                data: {
                    parent_id: categoryId
                },
                success: function (res) {
                    let $subcat = $("#subcategory_id");
                    $subcat.empty().append(
                        '<option value="" disabled>Select Sub Category..</option>');

                    $.each(res, function (key, subcategory) {
                        let selected = (selectedId && selectedId == subcategory.id) ?
                            'selected' : '';
                        $subcat.append(
                            `<option value="${subcategory.id}" ${selected}>${subcategory.name}</option>`
                        );
                    });
                }
            });
        }
        $("#category_id").change(function () {
            loadSubcategories($(this).val());
        });
        let initialCategory = $("#category_id").val();
        if (initialCategory) {
            loadSubcategories(initialCategory, selectedSubcategory);
        }

        let selectedWarehouse = "{{ old('warehouse_id', $data?->warehouse_id) }}";
        let selectedLocation = "{{ old('location_id', $data?->location_id) }}";

        function loadWarehouse(siteId, selectedId = null) {
            if (!siteId) return;

            $.ajax({
                url: "{{ route('get-child-Course', '') }}",
                type: "GET",
                data: {
                    parent_id: siteId
                },
                success: function (res) {
                    let $warehouse = $("#warehouse_id");
                    $warehouse.empty().append(
                        '<option value="" disabled selected>Select Warehouse..</option>');

                    $.each(res, function (key, warehouse) {
                        $warehouse.append(
                            `<option value="${warehouse.id}" data-location='${JSON.stringify(warehouse.location)}'
                           ${selectedId == warehouse.id ? "selected" : ""}> ${warehouse.name} </option>`
                        );
                    });
                    if (selectedId) {
                        $warehouse.trigger("change");
                    }
                }
            });
        }


        // On Site change → reload warehouses
        $("#site_id").change(function () {
            loadWarehouse($(this).val());
        });

        // On Warehouse change → update location without AJAX
        $("#warehouse_id").change(function () {
            let selectedOption = $(this).find(":selected");
            let location = selectedOption.data("location");

            if (location) {
                $("#location_id_view").val(location.location_name);
                $("#location_id").val(location.id);
            } else {
                $("#location_id_view").val('');
                $("#location_id").val('');
            }
        });

        // For edit case
        let initialSite = $("#site_id").val();
        if (initialSite) {
            loadWarehouse(initialSite, selectedWarehouse);
        }
    });
</script>
<!-- <script>
    const galleryInput = document.getElementById('gallery_images');
    const galleryError = document.getElementById('gallery-image-errors');
    const galleryPreviewContainer = document.getElementById('gallery-preview-container');
    const galleryPreviewList = document.getElementById('gallery-preview-list');

    galleryInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const maxCount = 6;

        // Reset preview and error
        galleryPreviewList.innerHTML = "";
        galleryError.textContent = "";
        galleryError.classList.add("hidden");

        if (files.length > maxCount) {
            galleryError.textContent = `You can upload a maximum of ${maxCount} images.`;
            galleryError.classList.remove("hidden");
            galleryInput.value = "";
            galleryPreviewContainer.classList.add("hidden");
            return;
        }

        let validCount = 0;

        files.forEach((file, index) => {
            if (!allowedTypes.includes(file.type)) {
                galleryError.textContent = "Only JPG, PNG, and SVG images are allowed.";
                galleryError.classList.remove("hidden");
                return;
            }

            if (file.size > maxSize) {
                galleryError.textContent = `Image "${file.name}" exceeds 2MB.`;
                galleryError.classList.remove("hidden");
                return;
            }

            validCount++;

            const reader = new FileReader();
            reader.onload = function (event) {
                const imageUrl = event.target.result;

                const listItem = document.createElement('li');
                listItem.className = "relative group border rounded overflow-hidden";

                listItem.innerHTML = `
                    <div class="relative">
                        <img src="${imageUrl}" alt="preview" class="w-full h-32 object-cover rounded border">
                        <div class="absolute top-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition">
                            <a href="${imageUrl}" target="_blank" title="View" class="bg-white p-1 rounded-full shadow hover:text-blue-600"><i class="ri-eye-line text-lg"></i></a>
                            <button type="button" class="bg-white p-1 rounded-full shadow hover:text-red-600" title="Delete"onclick="this.closest('li').remove(); checkIfPreviewEmpty();">
                            <i class="ri-delete-bin-line text-lg"></i></button>
                        </div>
                    </div>
                    <p class="text-xs mt-1 text-center truncate">${file.name}</p>
                 `;

                galleryPreviewList.appendChild(listItem);
            };

            reader.readAsDataURL(file);
        });

        galleryPreviewContainer.classList.toggle('hidden', validCount === 0);
    });

    function checkIfPreviewEmpty() {
        const galleryPreviewList = document.getElementById('gallery-preview-list');
        const galleryPreviewContainer = document.getElementById('gallery-preview-container');
        if (galleryPreviewList.children.length === 0) {
            galleryPreviewContainer.classList.add('hidden');
        }
    }
</script> -->
<script>
    function validateFileInput(inputId, errorId, allowedTypes, maxSizeMB) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        const maxSize = maxSizeMB * 1024 * 1024;

        input.addEventListener('change', function () {
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
        input.className = "flex-1 rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring focus:ring-blue-200 focus:border-blue-500 transition";

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
<script>
    // Character counter for Course Name
    const courseName = document.getElementById('course_name');
    const charCount = document.getElementById('course-name-count');
    courseName.addEventListener('input', () => {
        const len = courseName.value.length;
        const max = courseName.dataset.maxlength || 100;
        charCount.textContent = `${len} / ${max}`;
    });

    // Image preview for Feature Image with remove option
    const featureInput = document.getElementById('feature_image');
    const preview = document.getElementById('feature_image_preview');

    // Create remove button dynamically
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.textContent = 'Remove';
    removeBtn.className = 'mt-2 px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition';
    removeBtn.style.display = 'none'; // hide initially
    preview.insertAdjacentElement('afterend', removeBtn);

    removeBtn.addEventListener('click', () => {
        featureInput.value = ''; // reset file input
        preview.src = '';
        preview.classList.add('hidden');
        removeBtn.style.display = 'none';
    });

    featureInput.addEventListener('change', () => {
        const file = featureInput.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            removeBtn.style.display = 'inline-block'; // show remove button
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            removeBtn.style.display = 'none';
        }
    });
</script>
@endsection
