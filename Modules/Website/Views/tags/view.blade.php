@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Tags List'),[__('common.home'), __('website.Blog List'), __('website.Blog View')],['dashboard','tags-list']) @endphp
    @section('back_button')
        @php echo back_button('tags-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{route('tags-edit',['id'=>encrypt($data?->id)])}}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
        <div class="text-xs w-full mt-3 ">
            <div class="grid sm:grid-cols-12 md:grid-cols-2 lg:grid-cols-2 gap-5 w-full">
                <div class="w-full">
                    <label class="form-label">{{ __('website.Blog List') }}</label>
                    <p class="my-2">{{$data->name}}</p>
                </div>
              
                <div class="w-full">
                    <label class="form-label">{{ __('common.status') }}</label>
                    <p class="my-2">{{$data->status}}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('website.Blog Description') }}</label>
                    <p class="my-2">{{$data->description}}</p>
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
