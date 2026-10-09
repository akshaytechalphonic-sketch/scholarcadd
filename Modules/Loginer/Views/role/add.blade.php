@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @if ($data)
            @php echo breadcrums('common.role-list',['common.home','company.role-list','common.role-list'],['dashboard','roles-list']) @endphp
        @else
            @php echo breadcrums('common.create-role',['common.home','company.role-list','common.-role'],['dashboard','roles-list']) @endphp
        @endif
    @section('back_button')
        @php echo back_button('roles-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">        
        @if ($data)
        <form action="{{ route('roles-update') }}" method="post" enctype="multipart/form-data">
        @else
        <form action="{{ route('roles-store') }}" method="post" enctype="multipart/form-data">
        @endif
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.role') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input type="hidden" name="id"
                        @if ($data) value="{{ encrypt($data?->id) }}" @endif>
                    <input class="input-box form-input mt-1" name="name"
                        @if ($data?->name) value="{{ $data?->name }}" readonly @else  maxlength="30" required @endif>
                    </input>
                </div>
            </div>
            <div class="mt-3 border">
                <table class="w-full table-fixed">
                    <thead class="bg-primary text-white w-full">
                        <tr>
                            <th class="w-12 p-2 text-left">
                                <input type="checkbox" class="rounded-sm cursor-pointer" />
                            </th>
                            <th class="p-2 text-left">Permission</th>
                        </tr>
                    </thead>
                </table>
                <div class="max-h-[80vh] overflow-y-auto">
                    <table class="w-full table-fixed">
                        <tbody>
                            
                            @foreach($permissions as $permission)
                            <tr>
                                <td class="w-12 p-2 border-t border-gray-200">
                                    <input type="checkbox" name="permissions[]" value="{{$permission->name}}" class="rounded-sm cursor-pointer" 
                                     @if(in_array($permission->name , $assign_pers)) checked @endif />
                                </td>
                                <td class="p-2 border-t border-gray-200">{{$permission->name}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>






            <div class="flex justify-end py-2">
                <div class="flex gap-2">
                    <button type="reset" class="btn-primary-sm">{{ __('common.reset') }}</button>
                    <button type="submit" class="btn-success-sm">{{ __('common.submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.image_validation_js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const masterCheckbox = document.querySelector("thead input[type='checkbox']");
        const rowCheckboxes = document.querySelectorAll("tbody input[type='checkbox']");
        masterCheckbox.addEventListener("change", function() {
            rowCheckboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        });
        rowCheckboxes.forEach(cb => {
            cb.addEventListener("change", function() {
                if (!this.checked) {
                    masterCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
                    masterCheckbox.checked = allChecked;
                }
            });
        });
    });
</script>
@endsection
