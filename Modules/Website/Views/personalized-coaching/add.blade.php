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
            <form action="{{ route('page-update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name= "page_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('page-store') }}" method="post" enctype="multipart/form-data">
        @endif
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
                <label for="" class=" text-sm font-semibold text-black">Section One Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                    name="">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Banner Content
                </label>
                <textarea type="text" class="form-input mt-1 description-validation" value="" name=""
                    ></textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section One Content
                </label>
                <textarea type="text" class="form-input mt-1 description-validation" value="" name=""
                    ></textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section Two Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                    name="">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Section Two Sub Heading
                </label>
                <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                    name="">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Base Text
                </label>
                <input type="text" class="limited-input form-input mt-1" value="" data-maxlength="100"
                    name="">
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>
        </div>
        <div id="consultingDiv">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2 content-wrapper">
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Title</label>
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
@endsection
