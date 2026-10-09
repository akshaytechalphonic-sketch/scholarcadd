@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Landing Page Sections ',['common.home','Landing Page Sections'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('landingpage.index') @endphp
    @endsection
</div>
<div class="mt-4">


    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md mb-2"
        open>
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Add Section</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <form action="{{ route('section.update', encrypt($section?->id)) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                 <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.Select Category') }}
                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                    <select class="select-single mt-1 form-input" style="width:100%" name="page_id" >
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}" @if ($section?->page_id == $page->id) selected @endif> {{ $page->title }}</option>
                        @endforeach
                    </select>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Section</label>
                        <input type="text" class="input-box form-input mt-1 form-control-file" name="section" value="{{ $section?->section }}">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label</label>
                        <input type="text" class="input-box form-input mt-1 form-control-file" name="label" value="{{ $section?->label }}">
                    </div>

                    <div class="w-full relative z-10">
                        <label class="text-sm font-semibold text-black">Heading</label>
                        <input type="text" class="input-box form-input mt-1" name="heading" value="{{ $section?->heading }}">
                    </div>



                    <div class="w-full relative z-10">
                        <label class="text-sm font-semibold text-black">Button Label</label>
                        <input class="w-full form-input description-validation mt-1" name="button_label" value="{{ $section?->button_label }}"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 1 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image One</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_one"
                            onChange="validateFile(this,'image_document',5);" multiple>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label One</label>
                        <input class="w-full form-input description-validation mt-1" name="label_one" value="{{ $section?->label_one }}"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 2 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Two</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_two"
                            onChange="validateFile(this,'image_document',5);" multiple>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Two</label>
                        <input class="w-full form-input description-validation mt-1" name="label_two" value="{{ $section?->label_two }}"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 3 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Three</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_three"
                            onChange="validateFile(this,'image_document',5);" multiple>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Three</label>
                        <input class="w-full form-input description-validation mt-1" name="label_three" value="{{ $section?->label_three }}"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 4 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Four</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_four"
                            onChange="validateFile(this,'image_document',5);" multiple>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Four</label>
                        <input class="w-full form-input description-validation mt-1" name="label_four" value="{{ $section?->label_four }}"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 5 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Five</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_five"
                            onChange="validateFile(this,'image_document',5);" multiple>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Five</label>
                        <input class="w-full form-input description-validation mt-1" name="label_five" value="{{ $section?->label_five }}"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>
                </div>
                 <div class="w-full relative z-10">
                        <label class="text-sm font-semibold text-black">Description</label>
                        <textarea class="w-full form-input summernote mt-1" name="description">{!! $section?->description  !!}</textarea>
                    </div>
                <div class="flex justify-end mt-4">
                    <div class="flex gap-2">
                        <button type="reset" class="btn-primary-sm flex items-center gap-1">
                            <i class="ri-refresh-line"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </details>


</div>





@endsection
@section('scripts')
 @include('layouts.summer_note_js')
@endsection
