@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Blog List'),[__('common.home'), __('website.Blog List'), __('website.Blog View')],['dashboard','knowledge-center-list']) @endphp
    @section('back_button')
        <a href="{{ route('knowledge-center-list') }}">
            <button type="button"
                class="flex gap-1 items-center px-4 py-2
                   text-sm font-medium text-gray-900 hover:bg-gray-100">
                <i class="ri-arrow-left-line"></i> Back
            </button>
        </a>
    @endsection
    @section('extra_buttons')
        <a href="{{ route('knowledge-center-edit', ['id' => encrypt($data?->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
        <div class="text-xs w-full mt-3 ">
            <div class="grid sm:grid-cols-12 md:grid-cols-2 lg:grid-cols-2 gap-5 w-full">
                <div class="w-full">
                    <label class="form-label">{{ __('website.Blog List') }}</label>
                    <p class="my-2">{{ $data->title }}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('website.Meta Title') }}</label>
                    <p class="my-2">{{ $data?->meta_title }}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('common.status') }}</label>
                    <p class="my-2">{{ $data->status }}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('website.Blog Description') }}</label>
                    <p class="my-2">{{ $data->meta_description }}</p>
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
