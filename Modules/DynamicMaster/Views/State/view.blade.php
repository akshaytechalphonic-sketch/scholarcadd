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
        <h3 class="text-lg p-4 my-2 w-full border bg-white rounded-md "> {{ $flag }} State</h3>
        <div class="text-xs w-full  bg-white border rounded-md p-4">
            {{-- @if ($data) --}}
            {{-- <form action="{{ route('course-location-update') }}" method="post">
                <input type="hidden" name="location_id" value="{{ encrypt($data?->id) }}"> --}}
            {{-- @else --}}
            @php
                $readonly = $flag == 'View' ? 'readonly' : '';
            @endphp
            <form action="{{ route('state.update', ['state' => encrypt($state?->id)]) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 w-full my-2">

                    <!-- Name -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Name
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="limited-input form-input mt-1" name="name" data-maxlength="100"
                            value="{{ old('name', $state?->name) }}" {{ $readonly }} required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">URL
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="limited-input form-input mt-1" name="url" data-maxlength="255"
                            value="{{ old('url', $state?->url) }}" {{ $readonly }} required>
                        @error('url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Country
                            <span class="text-red-500 font-bold">*</span></label>
                        @if ($flag == 'Edit')
                            <select class="select-single mt-1" style="width:100%" name="country_id" {{ $readonly }}
                                required>
                                <option value="">Select Country...</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ old('country_id', $state?->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="limited-input form-input mt-1" name="url"
                                data-maxlength="255" value="{{ $state?->country?->name }}" {{ $readonly }}
                                required>
                        @endif
                        @error('country_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ISO Code -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">ISO Code</label>
                        <input type="text" class="number-only form-input mt-1" name="iso2" data-maxlength="6"
                            value="{{ old('iso2', $state?->iso2) }}" {{ $readonly }}>
                        @error('iso2')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Latitude -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Latitude
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="coord-input form-input mt-1" name="latitude" data-maxlength="100"
                            value="{{ old('latitude', $state?->latitude) }}" {{ $readonly }} required>
                        @error('latitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Longitude
                            <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" class="coord-input form-input mt-1" name="longitude" data-maxlength="100"
                            value="{{ old('longitude', $state?->longitude) }}" {{ $readonly }} required>
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WikiData ID -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">WikiData ID</label>
                        <input type="text" class="form-input mt-1" name="wikiDataId" data-maxlength="100"
                            {{ $readonly }} value="{{ old('wikiDataId', $state?->wikiDataId) }}">
                        @error('wikiDataId')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                @if ($flag == 'Edit')
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
                @endif
            </form>


        </div>
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@endsection
