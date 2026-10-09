
<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.add-workflow') }}
        </h5>
        <button type="button" onclick="toggle_model('add-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="add_modal">
        @csrf
            <div class="p-3 max-h-[85vh] overflow-y-auto">
                
                <div class="w-full">
                    <label class="text-sm">{{ __('company.workflow-code') }}<span class="text-danger">*</span></label>
                    <input type="text" id="add_code" name="w_code" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label class="text-sm">{{ __('common.apply-on') }}<span class="text-danger">*</span></label>
                    <select name="apply_on_id" id="add_apply_on_id" required style="width:100%" class="mt-2 form-input">
                        <option>{{ __('common.chooseoption') }}</option>
                        @foreach($apply_on as $apply)
                            <option value="{{$apply?->id}}">{{$apply?->workflow_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="text-sm">{{ __('company.workflow-name') }}<span class="text-danger">*</span></label>
                    <input type="text" id="add_name" name="w_name" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label class="text-sm">{{ __('company.workflow-status') }} <span class="text-danger">*</span></label>
                    <select name="w_status" id="add_status" required style="width:100%" class="mt-2 form-input">
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
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.edit-workflow-details') }}
        </h5>
        <button type="button" onclick="toggle_model('view-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
        <form id="edit_modal">   
        @csrf
        <div class="p-4">
            <input type="hidden" name="wf_id" id="wf_id" value="">
            <div class="w-full">
                <label class="text-sm">{{ __('company.workflow-code') }}</label>
                <input type="text" id="w_code" value="" readonly class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div> 
            <div class="w-full mt-4">
                <label class="text-sm">{{ __('common.apply-on') }}</label>
                <input type="text" id="w_apply_on" value="" readonly class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>
            <div class="w-full mt-4">
                <label class="text-sm">{{ __('company.workflow-name') }}</label>
                <input type="text" name="w_name" id="w_name" value="" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>
            <div class="w-full mt-4">
                <label class="text-sm"> {{ __('company.workflow-status') }}</label>
                <select name="w_status" id="w_status" required style="width:100%" class="mt-2 form-input">
                </select>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggle_model('view-sidebar')" class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                        {{ __('common.update') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
