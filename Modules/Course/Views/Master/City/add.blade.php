@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.City Add',['common.home','Course.City List','Course.City Add'],['dashboard','course-city-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if($data)
            <form action="{{route('course-city-update')}}" method="post">
            <input type="hidden" name= "location_id" value = "{{encrypt($data?->id)}}">
        @else
            <form action="{{route('course-city-store')}}" method="post">
        @endif
            @csrf
             <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.City Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" value="{{$data?->name}}" data-maxlength="100"
                        name="name" required>
                    </input>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
                {{-- <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Location Type') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="location_type" required>
                        <option value="">-- Select --</option>
                         @foreach ($location_type as $locationtype)
                            <option value="{{$locationtype->id}}" @if($data?->location_type == $locationtype->id) selected @endif>{{$locationtype->name}}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="w-full">
                    <label for=""
                           class="text-sm font-semibold text-black">{{ __('Course.Country Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select id="country_id" class="select-single mt-1" style="width:100%" name="country_id" required>
                        <option value="" disabled>Select Country..</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if (old('country_id', $data?->country_id) == $country->id) selected @endif>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for=""
                           class="text-sm font-semibold text-black">{{ __('Course.State Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select id="state_id" class="select-single mt-1" style="width:100%" name="state_id" required>
                        <option value="" disabled>Select State..</option>
                        @if ($data)
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" @if (old('state_id', $data?->state_id) == $state->id) selected @endif>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                {{-- <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Contact Person') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" value="{{$data?->contact_person}}" data-maxlength="100"
                        name="contact_person" required>
                    </input>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Contact Number') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="number-only form-input mt-1" value="{{$data?->contact_phone}}" data-maxlength="10"
                        name="contact_phone" required>
                    </input>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/10</div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Contact Email') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input required type="text" name="contact_email" value="{{$data?->contact_email}}" class="form-input mt-2 email-validation"
                        data-maxlength="60">
                    <div class="char-count text-xs text-gray-510 text-right mt-1"></div>
                    <div class="email-error" style="color: red; font-size: 0.9em;"></div>
                </div> --}}
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Latitude') }}
                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                    <input type="text" class="coord-input form-input mt-1" value="{{$data?->latitude}}" data-maxlength="100"
                        name="gps_latitude" >
                    </input>

                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Longitude') }}
                        {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                    <input type="text" class="coord-input form-input mt-1" value="{{$data?->longitude}}"  data-maxlength="100"
                         name="gps_longitude">
                    </input>

                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Status') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="status" required>
                        <option value=""></option>
                        <option value="Active" @if($data?->status=='Active') selected @endif>Active</option>
                        <option value="In active" @if($data?->status=='In Active') selected @endif>In Active </option>
                    </select>
                </div>
            </div>
            {{-- <div class="w-full mt-4">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Address') }}
                </label>
                <textarea type="text" class="form-input mt-1 description-validation" value="" name="address_line1"
                    data-maxlength="200">{{$data?->address_line1}}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
            </div> --}}
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
<script>

        $(document).ready(function () {
            $('#country_id').change(function () {
                var countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get-states') }}',
                        type: 'GET',
                        data: {country_id: countryId},
                        success: function (data) {
                            $('#state_id').empty();
                            $('#state_id').append('<option value="" disabled>Select State..</option>');
                            $.each(data, function (key, value) {
                                $('#state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#state_id').empty();
                    $('#state_id').append('<option value="" disabled>Select State..</option>');
                }
            });
        });

    </script>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
@endsection
