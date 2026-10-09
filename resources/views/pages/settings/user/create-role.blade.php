@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Add Role',['common.home','Add Role'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full mt-4">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Role Name</label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Description </label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>

                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Reference </label>
                    <select multiple="multiple" class="select-multi mt-1 " style="width: 100%">
                        <option disabled>Select an option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>


            </div>



            <div class="relative overflow-x-auto mt-4">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-2">
                                <input type="checkbox" class="form-select-sm rounded-sm cursor-pointer" name=""
                                    id="">
                            </th>
                            <th scope="col" class="px-6 py-2">
                                Permission
                            </th>
                            <th scope="col" class="px-6 py-2">
                                Reference
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b ">
                            <th class="px-6 py-2">
                                <input type="checkbox" class="form-select-sm rounded-sm cursor-pointer" name=""
                                    id="">
                            </th>
                            <td class="px-6 py-2">
                                Silver
                            </td>
                            <td class="px-6 py-2">
                                Laptop
                            </td>
                        </tr>
                        <tr class="bg-white border-b ">
                            <th class="px-6 py-2">
                                <input type="checkbox" class="form-select-sm rounded-sm cursor-pointer" name=""
                                    id="">
                            </th>
                            <td class="px-6 py-2">
                                Silver
                            </td>
                            <td class="px-6 py-2">
                                Laptop
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="flex gap-2 mt-2 justify-end">
                <button type="button"class="btn-primary-sm">
                    Cancel
                </button>
                <button type="button" class="btn-success-sm">Save</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('layouts.select2js')
@endsection
