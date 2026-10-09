@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('common.numbersequence',['common.home','common.numbersequence','common.numbersequence-list'],['dashboard','numbersequence-list']) @endphp
    @section('back_button')
        @php echo back_button('numbersequence-list') @endphp
    @endsection
    @section('extra_buttons')
        <button type="button" onclick="$.add_line()"
            class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class=" ri-add-line text-blue-500 font-semibold"></i>
            {{ __('common.add') }}
        </button>
        <button type="button"
            class="delete inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
            <i class="ri-delete-bin-line text-blue-500 font-semibold"></i>
            {{ __('common.delete') }}
        </button>
    @endsection
</div>
<form action="{{ route('numbersequence-store') }}" method="POST">
    @csrf
    <div class="text-xs w-full border bg-white rounded-md mt-4">
        <div class="p-2 ">
            <div class="overflow-x-auto p-2 max-h-[400px] overflow-y-auto">
                <table id="data-table-list" class="display table-auto w-full py-1 ">
                    <thead class="bg-primary text-white text-xs sticky top-0">
                        <tr class="text-start">
                            <th class="py-2 px-2"></th>
                            <th class="py-2 px-2">{{ __('company.number-sequence-code') }}</th>
                            <th class="py-2 px-2">{{ __('common.names') }}</th>
                            <th class="py-2 px-2">{{ __('company.constant') }}</th>
                            <th class="py-2 px-2">{{ __('company.range') }} </th>
                            <th class="py-2 px-2">{{ __('company.formate') }} </th>
                        </tr>
                    </thead>
                    <tbody class="text-xs" id="New-number-sequence-add-list"> </tbody>
                </table>
            </div>
            <div class="flex justify-end  p-3">
                <button type="submit" class="btn-success-sm">
                    <i class="ri-save-line"></i>
                    {{ __('common.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
    function check() {
        obj = $('table tr').find('span');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    var i = 0;
    $.add_line = function() {
        i++;
        $('#New-number-sequence-add-list').append(
            '<tr><td style="width: 40px;"><input type="checkbox" class="case"/></td>' +
            '<td style="width: 120px;"><input type="text" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" name="sequence_code[]" maxlength="30" required/></td>' +
            '<td style="width: 120px;"><input type="text" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" name="sequence_name[]" maxlength="25" required/></td>' +
            '<td style="width: 120px;"><input type="text" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" name="constant_no[]" id="const_' +
            i + '" maxlength="30" required onkeyup="$.concatenate_data(' + i + ')"/></td>' +
            '<td style="width: 80px;"><input type="text" class="number form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" name="range_no[]" required id="range_' +
            i +
            '" onkeypress="return (event.charCode > 51 && event.charCode < 58)" maxlength = "1" onblur="$.concatenate_data(' +
            i + ')"/></td>' +
            '<td style="width: 120px;"><input type="text" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5" name="format_code[]" id="formate_' +
            i + '" maxlength="30" readonly/></td>' +
            '</td></tr>');
    }

    $(".delete").on('click', function() {
        $('.case:checkbox:checked').parents("tr").remove();

        check();
    });

    $.concatenate_data = function(id) {
        var const_id = '#const_' + id;
        var range_id = '#range_' + id;
        var formate_id = 'formate_' + id;
        var constant_data = $(const_id).val();
        var range_data = $(range_id).val();
        var hash = '';
        while (range_data) {
            hash = hash + '#';
            range_data--;
        }
        document.getElementById(formate_id).value = constant_data + hash;
    }
</script>
@endsection
