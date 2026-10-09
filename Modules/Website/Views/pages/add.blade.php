@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
    @include('layouts.sweetalert_css')
    <style>
        .note-editable {
            white-space: pre-wrap !important;
            /* keeps line breaks and spaces */
        }

        ol {
            list-style: decimal !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        ul,
        menu {
            list-style: disc !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
@endsection
@section('content')
    <div>
        @php echo breadcrums('website.Page Add',['common.home','website.Page List','website.Page Add'],['dashboard','page-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('page-update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name= "page_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('page-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-1 w-full my-2">

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('website.Page Title') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->title }}"
                    data-maxlength="100" name="title" required>

                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>

            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.Meta Title') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->title }}"
                    data-maxlength="160" name="meta_title" required>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('website.Meta Description') }}
                    <textarea type="text" rows="8" class="form-input mt-1 description-validation" value=""
                        name="meta_description" data-maxlength="200">{{ $data?->meta_description }}</textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>

            <div class="w-full">
                <label for="blog_image" class="text-sm font-semibold text-black">
                    Upload Image
                </label>

                <input id="page_image" type="file" class="form-input mt-1" name="page_image"
                    accept=".png, .jpg, .jpeg, .svg">

                <p id="image-error" class="text-red-500 text-xs mt-1 hidden"></p>
                <div id="preview-container" class="mt-3 hidden">
                    <ul id="preview-list" class="space-y-2"></ul>
                </div>
            </div>

            @if ($data?->pages_img)
                <div class="w-full ">
                    <label for="Course_image" class="text-sm font-semibold text-black mb-2">
                        Uploded Image
                    </label>
                    <div class=" p-2 rounded-md border">
                        <a target="_blank" href="{{ route('secure-documents', ['path' => $data?->pages_img]) }}"><img
                                class="h-[50px] " src="{{ route('secure-documents', ['path' => $data?->pages_img]) }}"
                                alt=""></a>
                    </div>
                </div>
            @endif
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('common.status') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <select class="select-single mt-1" style="width:100%" name="status" id="status" required>
                    <option value=""></option>
                    <option value="Active" @if ($data?->status == 'Active') selected @endif>Active</option>
                    <option value="Consulting" @if ($data?->status == 'Consulting') selected @endif>Consulting</option>
                    <option value="Inactive" @if ($data?->status == 'Inactive') selected @endif>In Active </option>
                    <option value="Disclaimer-footer" @if ($data?->status == 'Disclaimer-footer') selected @endif>Footer</option>
                    <option value="All-Course" @if ($data?->status == 'All-Course') selected @endif>All Courses</option>
                </select>
            </div>


            {{--       <div id="consultingDiv"  class="w-full" style="display:none; margin-top:10px;">
    <input type="text" class="limited-input form-input mt-1" name="advantages1"  placeholder="Advantages 1"  data-maxlength="100">
    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
    <input type="text" class="limited-input form-input mt-1" name="advantages2" placeholder="Advantages 2"  data-maxlength="100">
    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
    <input type="text" class="limited-input form-input mt-1" name="advantages3" placeholder="Advantages 3"  data-maxlength="100">
    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
</div> --}}
            <div id="consultingDiv" class="w-full" style="display:none; margin-top:10px;">
                <div class="advantage-wrapper">
                    <input type="text" class="limited-input form-input mt-1" name="advantages[]"
                        placeholder="Advantages 1" value="{{ $data?->advantages1 }}" data-maxlength="100">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>

                <div class="advantage-wrapper">
                    <input type="text" class="limited-input form-input mt-1" name="advantages[]"
                        placeholder="Advantages 2" value="{{ $data?->advantages2 }}" data-maxlength="100">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>

                <div class="advantage-wrapper">
                    <input type="text" class="limited-input form-input mt-1" name="advantages[]"
                        placeholder="Advantages 3" value="{{ $data?->advantages3 }}" data-maxlength="100">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>

                <!-- Add Button -->
                <button type="button" id="addAdvantageBtn"
                    class="mt-3 px-3 py-1 text-xs bg-primary text-white rounded">
                    + Add Advantage
                </button>
            </div>

        </div>

        <div class="w-full mt-4">
            <label for="" class="text-sm font-semibold mb-4  text-black">{{ __('website.content') }}
            </label>
            <p id="word-limit-note" style="color:red; display:none; font-weight:600;">
                For All Courses the word limit is upto 150 words.
            </p>
            <textarea type="text" class="form-input description-validation-summernote summernote" value=""
                name="content">{!! $data?->content !!}</textarea>

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
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@include('layouts.sweetalert_js')
<script>
    $('#status').on('change', function() {
        let selectedValue = $(this).val();
        console.log(selectedValue); // for debug

        if (selectedValue === 'Consulting') {
            $('#consultingDiv').show(); // or .slideDown()
        } else {
            $('#consultingDiv').hide(); // or .slideUp()
        }
    });
</script>
<script>
    document.getElementById('addAdvantageBtn').addEventListener('click', function() {
        const container = document.getElementById('consultingDiv');
        const count = container.querySelectorAll('.advantage-wrapper').length + 1;

        const wrapper = document.createElement('div');
        wrapper.className = 'advantage-wrapper';

        wrapper.innerHTML = `
        <input type="text" class="limited-input form-input mt-1"
               name="advantages[]" placeholder="Advantages ${count}" data-maxlength="100">
        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
    `;

        container.insertBefore(wrapper, this);
    });

    // Character counter
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('limited-input')) {
            const max = e.target.dataset.maxlength;
            e.target.value = e.target.value.substring(0, max);
            e.target.nextElementSibling.textContent =
                `${e.target.value.length}/${max}`;
        }
    });
</script>

<script>
        $('#status').on('change', function () {

        let status = $(this).val();

        if(status === 'All-Course'){
            $('#word-limit-note').show();
        }else{
            $('#word-limit-note').hide();
        }

    });

    $(document).on('keydown', '.note-editable', function (e) {

        let status = $('#status').val();
        if(status !== 'All-Course') return;

        let text = $(this).text();
        let words = text.trim().split(/\s+/).filter(Boolean);

        // backspace allow
        if(e.key === 'Backspace'){
            return;
        }

        if(words.length >= 150){

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                text: 'Maximum 150 words allowed for All Courses'
            });

        }

    });

    $(document).on('paste', '.note-editable', function (e) {

        let status = $('#status').val();
        if(status !== 'All-Course') return;

        let pastedText = (e.originalEvent || e).clipboardData.getData('text');

        let pastedWords = pastedText.trim().split(/\s+/).filter(Boolean);

        let currentText = $(this).text();
        let currentWords = currentText.trim().split(/\s+/).filter(Boolean);

        if(currentWords.length + pastedWords.length > 150){

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                text: 'You cannot paste more than 150 words for All Courses'
            });

        }

    });

    $(document).ready(function(){

        if($('#status').val() === 'All-Course'){
            $('#word-limit-note').show();
        }

    });
</script>
@endsection
