@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Financial Dimension Add',['common.home','Financial Dimension Add'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">Code</label>
                    <input class="form-input mt-1 " required>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Related </label>
                    <select class="select-single mt-1 " style="width:100%">
                        <option value="">Select an option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>
            
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Enable </label>
                    <select class="select-single mt-1 " style="width:100%">
                        <option value="">Select an option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Related Table </label>
                    <select class="select-single mt-1 " style="width:100%">
                        <option value="">Select an option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </div>
                
            </div>

            <div class="pt-5 w-full">
                <div class="flex justify-between md:items-center md:flex-row flex-col mb-2">
                    <h2 class="text-lg font-semibold">Financial Dimension Line</h2>
                    <div class="flex gap-2">
                        <button type="button" class="btn-primary-sm">Submit</button>
                        <button type="button" onclick="addRow()"
                            class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800  dark:bg-blue-600 dark:hover:bg-primary dark:focus:ring-blue-800">
                            <i class="ri-add-circle-line"></i> Add
                        </button>

                        <button type="button" onclick="removeAllRows()"
                            class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800  dark:bg-blue-600 dark:hover:bg-primary dark:focus:ring-blue-800">
                            <i class="ri-delete-bin-7-line"></i> Remove All
                        </button>
                    </div>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="px-4 py-2 text-left text-sm">Sl. No.	</th>
                                <th class="px-4 py-2 text-left text-sm">Code</th>
                                <th class="px-4 py-2 text-left text-sm">Value</th>
                                <th class="px-4 py-2 text-left text-sm">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
<script>
    let rowCount4 = 0;

    function addRow() {
        rowCount4++;
        let tableBody = document.getElementById("table-body");
        let row = document.createElement("tr");
        row.className = "border-b";
        row.innerHTML = `
        <td class=" py-2">${rowCount4}.</td>
        <td class=" py-2"><input type="text" class="form-input"></td>
        <td class=" py-2"><input type="text" class="form-input"></td>
        <td class=" py-2 px-5">
            <button onclick="deleteRow(this)" class="text-red-500 hover:text-red-700">
                <i class="ri-delete-bin-6-line text-xl"></i>
            </button>
        </td>`;
        tableBody.appendChild(row);
    }

    function deleteRow(button) {
        let row = button.parentElement.parentElement;
        row.remove();
        updateRowNumbers();
    }

    function removeAllRows() {
        document.getElementById("table-body").innerHTML = "";
        rowCount = 0;
    }

    function updateRowNumbers() {
        let rows = document.querySelectorAll("#table-body tr");
        rowCount4 = 0;
        rows.forEach((row, index) => {
            row.children[0].innerText = index + 1;
            rowCount4++;
        });
    }
</script>
@section('scripts')
@include('layouts.select2js')
@endsection
