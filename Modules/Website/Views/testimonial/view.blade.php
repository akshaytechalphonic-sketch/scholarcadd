@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Blog List'),[__('common.home'), __('website.Blog List'), __('website.Blog View')],['dashboard','knowledge-center-list']) @endphp
    @section('back_button')
        @php echo back_button('knowledge-center-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('knowledge-center-edit', ['id' => encrypt($data?->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class=" mx-auto p-4 sm:p-3 md:p-3 space-y-4">


        <div class="bg-white p-2 sm:p-4 md:p-4 rounded-xl border space-y-2">
            <h2 class="text-xl sm:text-xl md:text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Testimonial
                Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="resourceCategory" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($couserdata as $couserdatas)
                            <option value="{{ $couserdatas->id }}" @if ($data?->resource_category == $couserdatas->id) selected @endif>
                                {{ $couserdatas->course_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-black">
                        {{ __('Course.Course-Country') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <select id="course_id" class="select-single mt-1 mb-8" style="width:100%" name="course_id" required>
                        <option value="" disabled>Select Category..</option>

                        @foreach ($CountryData as $CourseCounrty)
                            <option value="{{ $CourseCounrty?->id }}" @if (old('category_id', $data?->course_id) == $CourseCounrty?->id) selected @endif>
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
                <input type="text" id="resource_title" name="resource_title" required
                    value="{{ $data?->resource_title }}" data-maxlength="500"
                    class="limited-input form-input mt-1 w-full" />
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.comment') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea type="text" class="form-input mt-1 description-validation" value="" name="short_description"
                    data-maxlength="500">{{ $data?->short_description }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('common.status') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <select class="select-single mt-1" style="width:100%" name="status" required>
                    <option value=""></option>
                    <option value="Active" @if ($data?->status == 'Active') selected @endif>Active</option>
                    <option value="Inactive" @if ($data?->status == 'Inactive') selected @endif>In Active </option>
                </select>
            </div>



            </form>
        </div>
    </div>
@endsection
@section('scripts')
    @include('pages.common.toggle-sectionjs')
    @include('layouts.accordin')
@endsection
