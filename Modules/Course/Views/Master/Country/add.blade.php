@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Country Edit',['common.home','Course.Country List','Course.Country Edit'],['dashboard','course-country-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('course-country-update') }}" method="post">
                <input type="hidden" name= "location_id" value = "{{ encrypt($data?->id) }}">
            @else
                <form action="{{ route('Course-location-store') }}" method="post">
        @endif
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Country Name') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->name }}"
                    data-maxlength="100" name="name" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.ISO3') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->iso3 }}"
                    data-maxlength="3" name="iso3" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Numeric Code') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="coord-input form-input mt-1" value="{{ $data?->numeric_code }}"
                    data-maxlength="7" name="numeric_code" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.ISO2') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->iso2 }}"
                    data-maxlength="2" name="iso2" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Phonecode') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="coord-input form-input mt-1" value="{{ $data?->phonecode }}"
                    data-maxlength="5" name="phonecode" required>
                </input>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Capital') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->capital }}"
                    data-maxlength="100" name="capital" required>
                </input>
            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Currency') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->currency }}"
                    data-maxlength="100" name="currency" required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Value In Dollar') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <input
                    type="text"
                    class="coord-input form-input mt-1 value-in-dollar"
                    value="{{ number_format($data?->value_in_dollar ?? 0, 3, '.', '') }}"
                    data-maxlength="100"
                    name="value_in_dollar"
                    required
                    maxlength="9"
                    pattern="^\d{1,5}(\.\d{1,3})?$"
                    title="Max 5 digits before decimal, 3 after decimal"
                />
            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Currency Name') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->currency_name }}"
                    data-maxlength="100" name="currency_name"required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.currency_symbol') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->currency_symbol }}"
                    data-maxlength="100" name="currency_symbol"required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.T ID') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->tld }}"
                    data-maxlength="100" name="tld"required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Native') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->native }}"
                    data-maxlength="100" name="native"required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Region') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->region }}"
                    data-maxlength="100" name="region"required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Sub Region') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1"value="{{ $data?->subregion }}"
                    data-maxlength="100" name="subregion"required>
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Latitude') }}
                <input type="text" class="coord-input form-input mt-1" value="{{ $data?->latitude }}"
                    data-maxlength="100" name="latitude">
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Longitude') }}
                <input type="text" class="coord-input form-input mt-1" value="{{ $data?->longitude }}"
                    data-maxlength="100" name="longitude">
                </input>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.WikiDataId') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->wikiDataId }}"
                    data-maxlength="100" name="wikiDataId"required>
                </input>

            </div>

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
    document.addEventListener('DOMContentLoaded', function() {
    // ✅ Format on input
    document.querySelectorAll('.value-in-dollar').forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9.]/g, '');

            // ✅ Split by dot
            let parts = value.split('.');

            // ✅ Max 5 digits before decimal
            if (parts[0].length > 5) {
                parts[0] = parts[0].slice(0, 5);
            }

            // ✅ Max 3 digits after decimal
            if (parts[1] && parts[1].length > 3) {
                parts[1] = parts[1].slice(0, 3);
            }

            // ✅ Rejoin and update
            e.target.value = parts.join('.');
        });

        // ✅ Format on blur (display only)
        input.addEventListener('blur', function(e) {
            if (e.target.value) {
                e.target.value = parseFloat(e.target.value).toFixed(3);
            }
        });
    });
});

<script>


@endsection

