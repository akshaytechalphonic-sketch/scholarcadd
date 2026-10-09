@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.testimonial-add',['common.home','Course.testimonial-list','Course.testimonial-add'],['dashboard','knowledge-center-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection

    @if ($data)
        <form action="{{ route('testimonial-update') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name= "testimonial_id" value = "{{ encrypt($data?->id) }}">
        @else
            <form action="{{ route('testimonial-store') }}" method="post" enctype="multipart/form-data">
    @endif
    @csrf
    <div class=" mx-auto p-4 sm:p-3 md:p-3 space-y-4">


        <div class="bg-white p-2 sm:p-4 md:p-4 rounded-xl border space-y-2">
            <h2 class="text-xl sm:text-xl md:text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Testimonial
                Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="course_id" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($couserdata as $couserdatas)
                            <option value="{{ $couserdatas->id }}" @if ($data?->course_id == $couserdatas->id) selected @endif>
                                {{ $couserdatas->course_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-black">
                        {{ __('Course.Course-Country') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <select id="country_id" class="select-single mt-1 mb-8" style="width:100%" name="country_id"
                        required>
                        <option value="" disabled>Select Category..</option>

                        @foreach ($CountryData as $CourseCounrty)
                            <option value="{{ $CourseCounrty?->id }}" @if (old('country_id', $data?->country_id) == $CourseCounrty?->id) selected @endif>
                                {{ $CourseCounrty?->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                </div>
            </div>


            <div class="w-full">
                <label for="title" class="text-sm font-semibold text-black">
                    {{ __('common.name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="text" id="name" name="name" required value="{{ $data?->name }}"
                    data-maxlength="500" class="limited-input form-input mt-1 w-full" />
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.comment') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea type="text" class="summernote"  value="" name="comment" required data-maxlength="500">{{ $data?->comment }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">

                    <label for="title" class="text-sm font-semibold text-black">
                        Job Position
                    </label>
                    <input type="text" id="job_position" name="job_position" value="{{ $data?->job_position }}"
                        data-maxlength="100" class="limited-input form-input mt-1 w-full" />
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>

                </div>
                <div class="w-full">

                    <label for="title" class="text-sm font-semibold text-black">
                        Company Name
                    </label>
                    <input type="text" id="company_name" name="company_name" value="{{ $data?->company_name }}"
                        data-maxlength="100" class="limited-input form-input mt-1 w-full" />
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>

                </div>


            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Overview
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" id="course_overview_keywords" name="course_overview_keywords" required
                        value="{{ $data?->course_overview_keywords }}" data-maxlength="100"
                        class="limited-input form-input mt-1 w-full" />
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>

                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.status') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="status" required>
                        <option value=""></option>
                        <option value="Approved" @if ($data?->status == 'Approved') selected @endif>Approved</option>
                        <option value="Not Approved" @if ($data?->status == 'Not Approved') selected @endif>Not Approved
                        </option>
                    </select>
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
        </div>

    @endsection
    @section('scripts')
        @include('layouts.select2js')
        @include('layouts.summer_note_js')
        @include('layouts.image_validation_js')

        <script>
            $('#summernote').summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        </script>
        <script>
            $(document).ready(function() {
                let selectedSubcategory = "{{ old('subcategory_id', $data?->subcategory_id) }}";

                function loadSubcategories(categoryId, selectedId = null) {
                    if (!categoryId) return;
                    $.ajax({
                        url: "{{ route('get-child-Course', '') }}",
                        type: "GET",
                        data: {
                            parent_id: categoryId
                        },
                        success: function(res) {
                            console.log(res);
                            let $subcat = $("#subcategory_id");
                            $subcat.empty().append(
                                '<option value="" disabled>Select Sub Category..</option>');

                            $.each(res, function(key, subcategory) {
                                let selected = (selectedId && selectedId == subcategory.id) ?
                                    'selected' : '';
                                $subcat.append(
                                    `<option value="${subcategory.id}" ${selected}>${subcategory.category_name}</option>`
                                );
                            });
                        }
                    });
                }
                $("#category_id").change(function() {
                    loadSubcategories($(this).val());
                });
                let initialCategory = $("#category_id").val();
                if (initialCategory) {
                    loadSubcategories(initialCategory, selectedSubcategory);
                }
            });
        </script>
    @endsection
