@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @if ($data?->id)
            @php echo breadcrums('Course.FAQS-Category-edit',['common.home','Course.FAQS-Category-List','Course.FAQS-Category-edit'],['dashboard','bank-list']) @endphp
        @else
            @php echo breadcrums('Course.FAQS-Category-add',['common.home','Course.FAQS-Category-List','Course.FAQS-Category-add'],['dashboard','bank-list']) @endphp
        @endif
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('faqs-category-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('faqs-category-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="faqs_categotyId" required>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">

                <label for="" class="text-sm font-semibold text-black">{{ __('Course.FAQS-Category-name') }}
                    <span class="text-red-500 font-bold">*</span></label>

                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->category_name }}"
                    data-maxlength="100" name="faqs_category" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
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
@endsection
