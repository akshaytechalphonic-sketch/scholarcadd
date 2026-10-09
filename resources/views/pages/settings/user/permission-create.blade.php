@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Add New Permission',['common.home','Add New Permission'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full mt-4">
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Reference
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
                    <label for="" class="text-sm font-semibold text-black">Permission Name
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>
            </div>

            <div class="flex gap-2 mt-2 justify-end">
                <button type="button"class="btn-primary-sm">
                    Cancel
                </button>
                <button type="submit" class="btn-success-sm">Save</button>
            </div>

        </form>
    </div>

    <p>this is for select2 validation</p>
    <form>
        <div class="px-2">
            <label class="text-sm font-semibold text-black">Multi Select Example</label>
            <select class="select-multi mt-1" name="multi_example[]" multiple="multiple" style="width: 100%"
                required>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
            </select>
        </div>
        <div class="px-2">
            <label class="text-sm font-semibold text-black">Bid Opening Allow</label>
            <select class="select-single mt-1" name="bid_opening_allow" style="width: 100%" required>
                <option value="">Select an option</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
            </select>
        </div>

        <div class="px-2">
            <label class="text-sm font-semibold text-black">Tender Ranking Criteria</label>
            <select class="select-single mt-1" name="tender_ranking_criteria" style="width: 100%" required>
                <option value="">Select an option</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
            </select>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white">Submit</button>
    </form>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
@endsection
