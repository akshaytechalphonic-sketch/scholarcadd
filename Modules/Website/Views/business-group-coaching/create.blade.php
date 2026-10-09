@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('website.business-group-add',['common.home','website.business-group-coaching','website.business-group-add'],['dashboard','consulting-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        <form action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Banner Heading
                    </label>
                    <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                        name="">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Section One Heading First
                    </label>
                    <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                        name="">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Banner Content
                    </label>
                    <textarea type="text" class="form-input mt-1 description-validation" value="" name=""></textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Section One Content First
                    </label>
                    <textarea type="text" class="form-input mt-1 description-validation" value="" name=""></textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Section One Heading Second
                    </label>
                    <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                        name="">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Section Two Heading
                    </label>
                    <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                        name="">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Section One Content second
                    </label>
                    <textarea type="text" class="form-input mt-1 description-validation" value="" name=""></textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Section Two Main Heading
                    </label>
                    <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                        name="">
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
                    {{--  @if ($data?->pages_img)
                        <div class="w-full ">
                            <label for="Course_image" class="text-sm font-semibold text-black mb-2">
                                Uploded Image
                            </label>
                            <div class=" p-2 rounded-md border">
                                <a target="_blank"
                                    href="{{ route('secure-documents', ['path' => $data?->pages_img]) }}"><img
                                        class="h-[50px] "
                                        src="{{ route('secure-documents', ['path' => $data?->pages_img]) }}"
                                        alt=""></a>
                            </div>
                        </div>
                    @endif  --}}
                </div>
            </div>

            <div id="consultingDiv">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2 content-wrapper">
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Tab</label>
                        <input type="text" class="limited-input form-input mt-1" name="title[]">
                        <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Content</label>
                        <textarea class="form-input mt-1 description-validation" name="content[]"></textarea>
                        <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" id="addContentBtn" class="btn-success-sm">
                    <i class="ri-add-line"></i> Add
                </button>
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
                        <input type="text" class="limited-input form-input mt-1" name="pointer[]">

                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="addPointer" class="btn-success-sm">
                            <i class="ri-add-line"></i> Add
                        </button>
                    </div>
                @endif

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
            <label class="text-sm font-semibold text-black">Tab</label>
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
        wrapper.className = 'grid grid-cols-1 md:grid-cols-1 gap-1 w-full my-2 content-wrapper';

        wrapper.innerHTML = `
        <div class="w-full">
            <label class="text-sm font-semibold text-black">Pointer</label>
            <input type="text" class="limited-input form-input mt-1"
                   name="pointer[]" data-maxlength="100">
           
        </div>
    `;
        container.appendChild(wrapper);
    });
</script>
@endsection
