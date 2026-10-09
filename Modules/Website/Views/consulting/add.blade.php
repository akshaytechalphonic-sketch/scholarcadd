@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
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
        @php echo breadcrums('website.Consulting Add',['common.home','website.Consulting List','website.Consulting Add'],['dashboard','consulting-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('consulting-update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name= "consulting_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('consulting-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Meta Title
                </label>
                <textarea type="text" class="form-input mt-1 description-validation" name="meta_title">{{ $data?->meta_title }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Meta Description
                </label>
                <textarea type="text" class="form-input mt-1 description-validation" name="meta_description">{{ $data?->meta_description }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Banner Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->banner_Heading }}"
                    data-maxlength="200" name="banner_Heading">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">Section One Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->section_one_Heading }}"
                    data-maxlength="200" name="section_one_Heading">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Banner Content
                </label>
                <textarea type="text" class="form-input mt-1" name="banner_content">{{ $data?->banner_content }}</textarea>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section One Content
                </label>
                <textarea type="text" class="form-input mt-1" name="section_one_content">{{ $data?->section_one_content }}</textarea>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section Two Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->section_two_Heading }}"
                    name="section_two_Heading">
            </div>

            {{--  <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section Two Sub Heading
                </label>
                <input type="text" class="limited-input form-input mt-1"
                    value="{{ $data?->section_two_sub_Heading }}" name="section_two_sub_Heading">

            </div>  --}}
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section Two Content
                </label>
                <textarea type="text" class="limited-input form-input mt-1" value="" name="section_two_sub_Heading">{{ $data?->section_two_sub_Heading }}</textarea>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Base Text
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->base_text }}"
                    name="base_text">

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
        <div id="sectiontwoDiv" class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
            @if (!empty($data->advantages) && is_array($data->advantages))
                @foreach ($data->advantages as $advantage)
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Advantages</label>
                        <input type="text" class="limited-input form-input mt-1" value="{{ $advantage }}"
                            name="advantages[]" data-maxlength="100">
                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                    </div>
                @endforeach
            @else
                {{-- minimum one field --}}
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Advantages</label>
                    <input type="text" class="form-input mt-1 description-validation" name="advantages[]"
                        data-maxlength="100">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
            @endif
        </div>
        <div class="flex justify-end mt-2">
            <button type="button" id="pointerSectiontwo" class="btn-success-sm">
                <i class="ri-add-line"></i> Add
            </button>
        </div>
        <div id="consultingDiv">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2 content-wrapper">
                @php
                    $consultings =
                        $data && $data->consulting && $data->consulting->count() > 0
                            ? $data->consulting
                            : collect([null]); // force minimum 1
                @endphp

                @foreach ($consultings as $item)
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Title</label>
                        <input type="text" class="limited-input form-input mt-1" name="title[]"
                            value="{{ $item?->title }}">
                        <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Content</label>
                        <textarea class="form-input mt-1 description-validation" name="content[]">{{ $item?->content }}</textarea>
                        <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                    </div>

                    @if ($item)
                        <input type="hidden" name="sub_id[]" value="{{ $item->id }}">
                    @else
                        <input type="hidden" name="sub_id[]" value="">
                    @endif
                @endforeach

            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" id="addContentBtn" class="btn-success-sm">
                <i class="ri-add-line"></i> Add
            </button>
        </div>
        <div id="PointerDiv" class="grid grid-cols-1  gap-2 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Why Choose Us Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->chooseus_Heading }}"
                    data-maxlength="100" name="chooseus_Heading">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">Why Choose Us Content
                </label>
                <textarea type="text" class="summernote form-input mt-1" name="choose_us_content">{{ $data?->choose_us_content }}</textarea>

            </div>
        </div>
        <div id="PointerDiv" class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">

            @if (!empty($data->pointer) && is_array($data->pointer))
                @foreach ($data->pointer as $point)
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Pointer</label>
                        <input type="text" class="limited-input form-input mt-1" value="{{ $point }}"
                            name="pointer[]" data-maxlength="100">
                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                    </div>
                @endforeach
            @else
                {{-- minimum one field --}}
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Pointer</label>
                    <input type="text" class="limited-input form-input mt-1" name="pointer[]"
                        data-maxlength="100">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
            @endif

        </div>
        <div class="flex justify-end mt-2">
            <button type="button" id="addPointer" class="btn-success-sm">
                <i class="ri-add-line"></i> Add
            </button>
        </div>

        <div class="w-full">
            <label for="" class=" text-sm font-semibold text-black">FAQs
            </label>
            <textarea type="text" class="summernote form-input mt-1" name="faqs_contect">{{ $data?->faqs_contect }}</textarea>

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
<script>
    document.getElementById('addContentBtn').addEventListener('click', function() {
        const container = document.getElementById('consultingDiv');

        const wrapper = document.createElement('div');
        wrapper.className = 'grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2 content-wrapper';

        wrapper.innerHTML = `
        <div class="w-full">
            <label class="text-sm font-semibold text-black">Title</label>
            <input type="text" class="limited-input form-input mt-1"
                   name="title[]" data-maxlength="100">
            <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
        </div>

        <div class="w-full">
            <label class="text-sm font-semibold text-black">Content</label>
            <textarea class="form-input mt-1 description-validation"
                      name="content[]" data-maxlength="200"></textarea>
            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
        </div>
    `;
        container.appendChild(wrapper);
    });
</script>
<script>
    document.getElementById('addPointer').addEventListener('click', function() {
        const container = document.getElementById('PointerDiv');
        const wrapper = document.createElement('div');
        wrapper.className = 'grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2 content-wrapper';

        wrapper.innerHTML = `
        <div class="w-full">
            <label class="text-sm font-semibold text-black">Pointer</label>
            <input type="text" class="limited-input form-input mt-1"
                   name="pointer[]" data-maxlength="100">
            <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
        </div>
    `;
        container.appendChild(wrapper);
    });
</script>
<script>
    document.getElementById('pointerSectiontwo').addEventListener('click', function() {
        const container = document.getElementById('sectiontwoDiv');
        const wrapper = document.createElement('div');
        wrapper.className = 'grid grid-cols-1 gap-2 w-full my-2 content-wrapper';

        wrapper.innerHTML = `
        <div class="w-full">
            <label class="text-sm font-semibold text-black">Advantages</label>
            <input type="text" class="limited-input form-input mt-1"
                   name="advantages[]" data-maxlength="100">
            <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
        </div>
    `;
        container.appendChild(wrapper);
    });
</script>
@endsection
