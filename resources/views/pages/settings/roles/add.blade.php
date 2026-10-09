@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
            @php echo breadcrums('common.add-role',['common.home','common.add-role'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('role-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('roles-store') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2  w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.role-name') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="name" required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.description') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="description" required> </input>
                </div>
            </div>
            <div class="w-full mt-2">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.reference') }} <span
                        class="text-red-500 font-bold">*</span></label>
                <select class="select-single mt-1" style="width:100%" id="multiple-menus" required multiple="multiple">
                    <option value=""> </option>
                    @foreach ($all_menus as $menu)
                    <option value="{{ $menu?->id }}">{{ $menu?->menuname }}</option>
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
