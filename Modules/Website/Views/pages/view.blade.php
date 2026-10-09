@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Pages View'),[__('common.home'), __('website.Pages View'), __('website.Page View')],['dashboard','page-list']) @endphp
    @section('back_button')
        @php echo back_button('page-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('page-edit', ['id' => encrypt($data?->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
        <div class="text-xs w-full mt-3 ">
            <div class="grid sm:grid-cols-12 md:grid-cols-2 lg:grid-cols-2 gap-5 w-full">
                <div class="w-full">
                    <label class="form-label">{{ __('website.Page Title') }}</label>
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
                    <label class="form-label">{{ __('website.Page Description') }}</label>
                    <p class="my-2">{{ $data->meta_description }}</p>
                </div>
                <div class="w-full">
                    <label class="form-label">{{ __('website.content') }}</label>
                    <p class="my-2">{!! $data->content !!}</p>
                </div>

                @if ($data?->pages_img)
                    <div class="w-full ">
                        <label for="Course_image" class="text-sm font-semibold text-black mb-2">
                            Uploded Image
                        </label>
                        <div class=" p-2 rounded-md border">
                            <a target="_blank"
                                href="{{ route('secure-documents', ['path' => $data?->pages_img]) }}"><img
                                    class="h-[50px] "
                                    src="{{ route('secure-documents', ['path' => $data?->pages_img]) }}"
                                    alt=""></a>
                        </div>
                    </div>
                @endif


        </div>

    </div>
</div>
</div>
@endsection
@section('scripts')
@include('pages.common.toggle-sectionjs')
@include('layouts.accordin')
@endsection
