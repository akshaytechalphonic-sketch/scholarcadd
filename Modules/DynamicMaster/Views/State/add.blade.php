@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        <blade
            php|%20echo%20breadcrums(%26%2339%3BCourse.Country%20Add%26%2339%3B%2C%5B%26%2339%3Bcommon.home%26%2339%3B%2C%26%2339%3BCourse.Location%20List%26%2339%3B%2C%26%2339%3BCourse.Location%20Add%26%2339%3B%5D%2C%5B%26%2339%3Bdashboard%26%2339%3B%2C%26%2339%3BCourse-location-list%26%2339%3B%5D)%20%40endphp>
        @section('back_button')
            @phpechoback_button('back') @endphp
        @endsection
        <div class="text-xs w-full  bg-white border rounded-md p-4">
            {{-- @if ($data) --}}
            {{-- <form action="{{ route('course-location-update') }}" method="post">
                <input type="hidden" name="location_id" value="{{ encrypt($data?->id) }}"> --}}
            {{-- @else --}}
            @php
            @endphp
            <form action="{{ route('state.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 w-full my-2">

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Name
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="limited-input form-input mt-1" name="name"
                            data-maxlength="100" required>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">URL
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="limited-input form-input mt-1" name="url" data-maxlength="255"
                            required>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Country
                            <span class="text-red-500 font-bold">*</span></label>
                        <select class="select-single mt-1" style="width:100%" name="country_id" required>
                            <option value="">Select Country...</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">ISO Code</label>
                        <input type="text" class="number-only form-input mt-1" name="iso2" data-maxlength="6">
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Latitude
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="coord-input form-input mt-1" name="latitude"
                            data-maxlength="100" required>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Longitude
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="coord-input form-input mt-1" name="longitude"
                            data-maxlength="100" required>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">WikiData ID</label>
                        <input type="text" class="form-input mt-1" name="wikiDataId" data-maxlength="100">
                    </div>

                </div>

                <div class="flex justify-end mt-4">
                    <div class="flex gap-2">
                        <button type="reset" class="btn-primary-sm flex items-center gap-1">
                            <i class="ri-refresh-line"></i> {{ __('common.reset') }}
                        </button>
                        <button type="submit" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i> {{ __('common.submit') }}
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
@endsection
