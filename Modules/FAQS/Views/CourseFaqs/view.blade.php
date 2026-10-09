@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.course-FAQS-view',['common.home','Course.course-FAQS-list','Course.course-FAQS-view'],['dashboard','bank-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('faqs-course-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('faqs-course-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="course_faqs_id" required>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">



            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Course Category') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="course_category_id" class="select-single mt-1" style="width:100%" name="course_category_id"
                    required>
                    <option value="" disabled>Select Category..</option>
                    @foreach ($courseCategory as $category)
                        <option value="{{ $category->id }}" @if (old('course_category_id', $data?->course_category) == $category->id) selected @endif>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Country Name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="category_id" class="select-single mt-1" style="width:100%" name="counrty_id" required>
                    <option value="" disabled>Select Category..</option>
                    @foreach ($courseCountry as $country)
                        <option value="{{ $country->id }}" @if (old('category_id', $data?->country_id) == $country->id) selected @endif>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.State Name') }}
                </label>
                <select id="subcategory_id" class="select-single mt-1 mb-8" style="width:100%" name="sub_state_id">
                    <option value="" disabled>Select Sub Category..</option>
                </select>
                <div class="char-count text-xs text-gray-500 text-right mt-1"> </div>
            </div>

        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('common.title') }}
                <span class="text-red-500 font-bold">*</span></label>
            <input type="text" class="limited-input form-input mt-1" value="{{ $data?->title }}" readonly>
            </input>

        </div>
        <br />
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }}
                <span class="text-red-500 font-bold">*</span></label>
            <textarea disabled class="form-input mt-1 description-validation-summernote summernote" name="description">{!! $data?->description !!}</textarea>

        </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.tab2_js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            toolbar: false,
            airMode: false,
            disableResizeEditor: true,
            disableDragAndDrop: true,
            height: 200,
            disable: true
        });
        let selectedSubcategory = "{{ old('subcategory_id', $data?->state_id) }}";

        function loadSubcategories(categoryId, selectedId = null) {
            if (!categoryId) return;
            $.ajax({
                url: "{{ route('get-child-state', '') }}",
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
                            `<option value="${subcategory.id}" ${selected}>${subcategory.name}</option>`
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
