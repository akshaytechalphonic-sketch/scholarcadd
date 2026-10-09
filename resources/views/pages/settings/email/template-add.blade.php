@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Email Template Add',['common.home','Email Template Add'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full mt-4">
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Email Master
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <select class="select-single mt-1" name="bid_opening_allow" style="width: 100%" required>
                        <option value="">Select an option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Available Variables </label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Name
                        <span class="text-red-500 font-bold">*</span>
                         </label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Subject
                        <span class="text-red-500 font-bold">*</span>

                    </label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>
            </div>
            <div class="w-full mt-4">
                <label for="" class="text-sm font-semibold text-black">Content
                    <span class="text-red-500 font-bold">*</span>

                </label>
                <textarea class="summernote mt-2"></textarea>
            </div>
            <div class="flex gap-2 mt-2 justify-end">
                <button type="reset"class="btn-primary-sm">
                    Cancel
                </button>
                <button type="submit" class="btn-success-sm">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.summer_note_js')
@endsection
