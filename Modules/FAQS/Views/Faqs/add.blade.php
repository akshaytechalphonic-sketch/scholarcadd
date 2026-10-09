@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.FAQS-general-Add',['common.home','Course.FAQS-general-list','Course.FAQS-general-Add'],['dashboard','bank-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('faqs-general-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('faqs-general-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="faqsGeneral_id" required>
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
                    @foreach ($courseCountry as $category)
                        <option value="{{ $category->id }}" @if (old('course_category_id', $data?->course_id) == $category->id) selected @endif>
                            {{ $category->course_title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.FAQS Category') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="faqs_category_id" class="select-single mt-1" style="width:100%" name="faqs_category_id"
                    required>
                    <option value="" disabled>Select Category..</option>
                    @foreach ($faqsCategory as $category)
                        <option value="{{ $category->id }}" @if (old('faqs_category_id', $data?->category_id) == $category->id) selected @endif>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.title') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->title }}"
                    data-maxlength="100" name="faqs_title" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
            </div>
        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }}
                <span class="text-red-500 font-bold">*</span></label>
            <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" name="description">{!! $data?->description !!}</textarea>
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
@include('layouts.summer_note_js')
@endsection
