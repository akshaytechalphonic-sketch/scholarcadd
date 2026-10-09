{{-- <div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
    <div id="add-workflow-setup" class="drawer fixed z-50 bg-white dark:bg-gray-800 transition-transform hidden w-96 h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-lg shadow-lg flex flex-col" tabindex="-1">
        <div class="flex justify-between items-center px-2 py-4 border-b">
            <h5 class="text-base font-semibold text-gray-500 dark:text-gray-400">{{ __('company.add-workflow') }} </h5>
            <button type="button" class="close-drawer-btn close-btn">
                <i class="ri-close-line"></i>
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
                    <select name="apply_on_id" id="add_apply_on_id" required style="width:100%" class="select-single mt-2 ">
                        <option>{{ __('common.chooseoption') }}</option>
                       
                    </select>
                </div>
                <div class="w-full">
                    <label class="text-sm">{{ __('company.workflow-name') }}<span class="text-danger">*</span></label>
                    <input type="text" id="add_name" name="w_name" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label class="text-sm">{{ __('company.workflow-status') }} <span class="text-danger">*</span></label>
                    <select name="w_status" id="add_status" required style="width:100%" class="select-single mt-2 ">
                        <option>{{ __('common.chooseoption') }}</option> 
                        <option value="Active">{{ __('common.active') }}</option>
                        <option value="Inactive">{{ __('common.inactive') }}</option>
                    </select>
                </div>
    
            
            </div>
            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <button type="button" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i> {{ __('common.close') }}</button>
                <button type="submit" class="btn-success-sm close-drawer-btn save-btn"><i class="ri-save-line"></i> {{ __('common.add') }}</button>
            </div>
        </form>
    </div>
</div>

<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white w-80" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.edit-workflow-details') }}
        </h5>
        <button type="button" onclick="togglview_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
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
            <select name="w_status" id="w_status" required style="width:100%" class="select-single mt-2 ">            
            </select>
        </div>

        <div class="mt-[50vh]">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="togglview_modelSidebar()" class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                    {{ __('common.close') }}
                </button>
                <button type="submit" onclick="togglview_modelSidebar()" class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                    {{ __('common.update') }}
                </button>
            </div>
        </div>
    </div>
</div> --}}