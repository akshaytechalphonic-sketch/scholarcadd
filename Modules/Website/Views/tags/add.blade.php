@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('website.Tags Add',['common.home','website.Tags List','website.Tags Add'],['dashboard','tags-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('tags-update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name= "tags_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('tags-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-1 gap-5 w-full my-2">

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.Title') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->name }}"
                    data-maxlength="100" name="name" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('website.Meta Description') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea type="text" class="form-input mt-1 description-validation" value=""
                name="description" required data-maxlength="200">{{ $data?->description }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
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
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@endsection
