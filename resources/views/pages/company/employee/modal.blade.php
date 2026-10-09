
<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white w-80" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.create-user') }}
        </h5>
        <button type="button" onclick="togglview_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form action="{{ route('create-user-by-emp') }}" method="post" enctype="multipart/form-data">
    @csrf
        <div class="p-4">
            <input type="hidden" id="emp_id" name="emp_id" readonly required>
            <div class="w-full">
                <label class="text-sm">{{ __('company.emp-code') }}</label>
                <input type="text" disabled id="emp_code" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>
            <div class="w-full">
                <label class="text-sm">{{ __('company.emp-name') }}</label>
                <input type="text" disabled id="emp_name" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>
     
            <div class="w-full mt-4">
                <label class="text-sm">{{ __('company.role-list') }}</label>
                <select class="select-multi" style="width:100%" name="role_ids[]" multiple="multiple" data-placeholder="{{__('common.chooseoption')}}">
                    @foreach ($role as $roles)
                        <option value="{{$roles?->name}}">{{$roles?->name}}</option>
                    @endforeach
                </select> 
            </div>
    
            <div class="mt-[50vh]">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="togglview_modelSidebar()" class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" class="master_update px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                        {{ __('common.create') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>