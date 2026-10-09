@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
<div>
    @php echo breadcrums('company.add-permission',['common.home','company.add-permission'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('permission-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('permission.store') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2  w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('admin.menu-setting') }} <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="menu_setting" required>
                        <option value=""> </option>
                        @foreach ($menuset as $menuName)
                            <option value="{{ $menuName?->id }}"
                                @if ($permission?->menu_id == $menuName?->id) selected @endif>{{ $menuName?->menuname }}
                            </option>
                        @endforeach
                    </select>
                </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('admin.permission-name') }} <span class="text-red-500 font-bold">*</span></label>
                @if ($permission?->name)
                <input type="text" name="permission_name" class="input-box form-input mt-1" value="{{ $permission?->name }}" readonly>
                @else
                <input type="text" name="permission_name" class="input-box form-input mt-1">
                @endif
            </div>
        </div>

            <div class="flex justify-end py-2">
                <div class="flex gap-2">
                    <button type="reset" class="btn btn-sm btn-secondary waves-effect waves-light">{{ __('common.reset') }}</button>
                    @if ($permission)
                        <input type="hidden" name="permission_id" value="{{ base64_encode(convert_uuencode($permission?->id)) }}">
                        <button type="submit" class="btn-success-sm">{{ __('common.update') }}</button>
                    @else
                        <button type="submit" class="btn-success-sm">{{ __('common.create') }}</button>
                    @endif

                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
@include('components.formvalidationjs')


@endsection
