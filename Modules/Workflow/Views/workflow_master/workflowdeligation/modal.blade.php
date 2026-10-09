<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.add-workflow-deligation') }}
        </h5>
        <button type="button" onclick="toggle_model('add-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="add_modal">
        @csrf
            <div class="p-3 max-h-[85vh] overflow-y-auto">

                <div class="w-full">
                    <label class="text-sm">{{__('company.scope')}}<span class="text-danger">*</span></label>
                    <select name="scope" id="scope" required style="width:100%" class="mt-2 form-input">
                        <option>{{ __('common.chooseoption') }}</option>
                        <option value="0">All</option>
                         @foreach($scopes as $scope)
                        <option value="{{ $scope->id }}">{{ $scope->workflow_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full mt-3">
                    <label class="text-sm">{{__('company.employee-name')}}<span class="text-danger">*</span></label>
                    <select name="employee_name" id="employee_name" required style="width:100%" class="mt-2 form-input">
                        <option>{{ __('common.chooseoption') }}</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full mt-3">
                    <label class="text-sm">{{__('company.delegate-to')}}</label>
                    <select name="delegate_to" id="delegate_to" required style="width:100%" class="mt-2 form-input">
                        <option>{{ __('common.chooseoption') }}</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full mt-3">
                    <label class="text-sm">{{ __('company.start-date') }}<span class="text-danger">*</span></label>
                    <input type="date" id="start_date" name="start_date" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>

                <div class="w-full mt-3">
                    <label class="text-sm">{{__('company.end-date')}}<span class="text-danger">*</span></label>
                    <input type="date" id="end_date" name="end_date" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>
                <div class="w-full mt-3">
                    <label class="text-sm">{{ __('company.workflow-status') }} <span class="text-danger">*</span></label>
                    <select name="status" id="add_status" required style="width:100%" class="mt-2 form-input">
                        <option>{{ __('common.chooseoption') }}</option>
                        <option value="Active">{{ __('common.active') }}</option>
                        <option value="Inactive">{{ __('common.inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <button type="button" onclick="toggle_model('add-sidebar')" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i> {{ __('common.close') }}</button>
                <button type="submit" class="btn-success-sm close-drawer-btn save-btn"><i class="ri-save-line"></i> {{ __('common.add') }}</button>
            </div>
        </form>
</div>


<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">


</div>
