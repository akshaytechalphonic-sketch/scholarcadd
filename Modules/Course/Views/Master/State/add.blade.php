@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.State Add',['common.home','Course.State List','Course.State Add'],['dashboard','course-state-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if($data)
            <form action="{{route('course-state-update')}}" method="post">
            <input type="hidden" name= "location_id" value = "{{encrypt($data?->id)}}">
        @else
            <form action="{{route('course-state-store')}}" method="post">
        @endif
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.State Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" value="{{$data?->name}}" data-maxlength="100"
                        name="name" required>
                    </input>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>

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
                    <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Type') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="statetype" required>
                        <option value=""></option>
                        <option value="State" @if($data?->type=='State') selected @endif>State</option>
                        <option value="territory" @if($data?->type=='territory') selected @endif>Territory </option>
                    </select>
                </div>


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
@endsection
