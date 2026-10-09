@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Contact List'),[__('common.home'), __('website.Contact List'), __('website.Contact View')],['dashboard','contact-list']) @endphp
    @section('back_button')
        @php echo back_button('contact-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{route('contact-edit',['id'=>encrypt($data?->id)])}}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
       <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-2">

            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('common.email') }}
                </label>
                <input type="email" class=" form-input mt-1 email-validation" value="{{ $data?->email }}"
                    data-maxlength="60" name="email" required>
                </input>
                <div class="char-count text-xs text-gray-510 text-right mt-1"></div>
                <div class="email-error" style="color: red; font-size: 0.9em;"></div>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.phone') }}
                </label>
                <input type="text" class="number-only form-input mt-1" value="{{ $data?->phone }}"
                    data-maxlength="10" name="phone" required>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/10</div>
                </input>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.timing') }}
                </label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->Hours }}"
                    data-maxlength="100" name="timing" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.facebook') }}
                </label>
                <input type="text" class=" form-input mt-1" value="{{ $data?->facebook }}"
                    name="facebook" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.youtube') }}
                </label>
                <input type="text" class=" form-input mt-1" value="{{ $data?->youtube }}"
                    name="youtube" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.X') }}
                </label>
                <input type="text" class=" form-input mt-1" value="{{ $data?->x }}"
                    name="x" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.instagram') }}
                </label>
                <input type="text" class=" form-input mt-1" value="{{ $data?->instagram }}"
                    name="instagram" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('website.linkedin') }}
                </label>
                <input type="text" class=" form-input mt-1" value="{{ $data?->linkedin }}"
                    name="linkedin" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('website.Meta Description') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea type="text" class="form-input mt-1 description-validation" value="" name="meta_description"
                    data-maxlength="200">{{ $data?->meta_description }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>

            <div class="w-full">
                <label for="blog_image" class="text-sm font-semibold text-black">
                    Upload Image
                </label>

                <input id="contact_image" type="file" class="form-input mt-1" name="contact_image"
                    accept=".png, .jpg, .jpeg, .svg">

                <p id="image-error" class="text-red-500 text-xs mt-1 hidden"></p>
                <div id="preview-container" class="mt-3 hidden">
                    <ul id="preview-list" class="space-y-2"></ul>
                </div>
            </div>

            @if ($data?->contact_img)
                <div class="w-full ">
                    <label for="Course_image" class="text-sm font-semibold text-black mb-2">
                        Uploded Image
                    </label>
                    <div class=" p-2 rounded-md border">
                        <a target="_blank"
                            href="{{ route('secure-documents', ['path' => $data?->contact_img]) }}"><img
                                class="h-[50px] "
                                src="{{ route('secure-documents', ['path' => $data?->contact_img]) }}"
                                alt=""></a>
                    </div>
                </div>
            @endif
            <div class="w-full">
                <label for="" class=" text-sm font-semibold text-black">{{ __('inventory.Status') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <select class="limited-input form-input mt-1" style="width:100%" name="status" required>
                    <option value=""></option>
                    <option value="Active" @if ($data?->status == 'Active') selected @endif>Active</option>
                    <option value="Inactive" @if ($data?->status == 'Inactive') selected @endif>In Active </option>
                </select>
            </div>

        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('common.address') }}
                <span class="text-red-500 font-bold">*</span></label>
            <textarea type="text" class="form-input mt-1 description-validation" value="" name="address"
                data-maxlength="200">{{ $data?->address }}</textarea>
            <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">{{ __('website.Google Map') }}
            </label>
            <textarea type="text" class="form-input mt-1  summernote" value=""
                name="map">{!! $data?->map !!}</textarea>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('pages.common.toggle-sectionjs')
@include('layouts.accordin')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@endsection
