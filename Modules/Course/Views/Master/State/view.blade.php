@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Location View'),[__('common.home'), __('Course.State List'), __('Course.State View')]) @endphp
    @section('back_button')
        @php echo back_button('course-state-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{route('course-state-edit',['id'=>encrypt($data?->id)])}}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
        <div class="text-xs w-full mt-3 ">
            <div class="grid sm:grid-cols-12 md:grid-cols-3 lg:grid-cols-4 gap-5 w-full">
                <div class="w-full">
                    <label class="form-label">{{ __('Course.State Name') }}</label>
                    <p class="my-2">{{$data->name}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Country Name') }}</label>
                    <p class="my-2">{{$data->country?->name ?? ''}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Type') }}</label>
                    <p class="my-2">{{$data->type}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Latitude') }}</label>
                    <p class="my-2">{{$data->latitude}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Longitude') }}</label>
                    <p class="my-2">{{$data->longitude}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('Course.Status') }}</label>
                    <p class="my-2">{{$data->status}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('pages.common.toggle-sectionjs')
@include('layouts.accordin')
@endsection
