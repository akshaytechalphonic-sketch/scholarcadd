@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
            @php echo breadcrums('company.edit-role',['common.home','company.edit-role'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('role-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('roles.update') }}" method="post">
            @csrf
            <input @if ($role?->id) value="{{ base64_encode(convert_uuencode($role?->id)) }}" @endif name="role_id" id="role_id" type="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2  w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.role-name') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" value="{{ $role?->name }}" readonly> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="description" value="{{ $role?->description }}"> </input>
                </div>
            </div>
            <div class="w-full mt-2">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.reference') }} <span
                        class="text-red-500 font-bold">*</span></label>
                <select class="select-single mt-1" style="width:100%" id="multiple-menus" required multiple="multiple">
                    <option value=""> </option>
                    <option value="All">{{ __('common.alll') }}</option>
                    @foreach ($all_menus as $menu)
                        <option value="{{ $menu?->id }}"
                            {{ in_array($menu?->id, $selected_menus) ? 'selected' : '' }}> {{ $menu?->menuname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <table class="w-full border-collapse mt-2">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="custome-tables"><input type="checkbox" class="form-control-sm" id="selectAll" style="min-height: 10px;"></th>
                        <th class="custome-tables">{{ __('common.permission') }}</th>
                        <th class="custome-tables">{{ __('common.reference') }}</th>
                    </tr>
                </thead>
                <tbody id="permissions_list">
                    @foreach ($all_permissions as $permission)
                    <tr>
                        <td><input type="checkbox" class="checkone" value="{{ $permission?->id }}" name="permission[]" checked></td>
                        <td>{{ $permission?->name }}</td>
                        <td>{{ $permission?->Menu_details?->menuname }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="flex justify-end py-2">
                <div class="flex gap-2">
                    <button type="submit" class="btn-success-sm">{{ __('common.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
@include('pages.settings.roles.extra_js')
@include('components.formvalidationjs')

@endsection
