@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Location View'),[__('common.home'), __('Course.Location List'), __('Course.Location View')],['dashboard','gl-entity-list']) @endphp
    @section('back_button')
        @php echo back_button('Course-location-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{route('Course-location-edit',['id'=>encrypt($data?->id)])}}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
        <div class="text-xs w-full mt-3 ">
            <div class="grid sm:grid-cols-12 md:grid-cols-3 lg:grid-cols-4 gap-5 w-full">
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Location Name') }}</label>
                    <p class="my-2">{{$data->location_name}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Location Type') }}</label>
                    <p class="my-2">{{$data?->locationType?->name}}</p>

                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.State') }}</label>
                    <p class="my-2">{{$data->state}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Postal Code') }}</label>
                    <p class="my-2">{{$data->postal_code}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Country') }}</label>
                    <p class="my-2">{{$data->country}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Contact Person') }}</label>
                    <p class="my-2">{{$data->contact_person}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Contact Number') }}</label>
                    <p class="my-2">{{$data->contact_number}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Contact Email') }}</label>
                    <p class="my-2">{{$data->contact_email}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Latitude') }}</label>
                    <p class="my-2">{{$data->gps_latitude}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Longitude') }}</label>
                    <p class="my-2">{{$data->gps_longitude}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Status') }}</label>
                    <p class="my-2">{{$data->status}}</p>
                </div>
            </div>
            <div class="w-full">
                    <label class="form-label">{{ __('Course.Address') }}</label>
                    <p class="my-2">{{$data->address_line1}}</p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('pages.common.toggle-sectionjs')
@include('layouts.accordin')
@endsection
