<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.add-tax-type') }}
        </h5>
        <button type="button" onclick="toggle_model('add-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="add_modal">
        @csrf
            <div class="p-3 max-h-[85vh] overflow-y-auto">

                <div class="w-full">
                    <label class="text-sm">{{__('common.name')}}<span class="text-danger">*</span></label>
                     <input type="text" id="tax_type_name" name="tax_type_name" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label class="text-sm">{{__('common.code')}}<span class="text-danger">*</span></label>
                     <input type="text" id="tax_type_code" name="tax_type_code" required class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>

                <div class="w-full">
                    <label class="text-sm">{{__('common.description')}}</label>
                    <textarea id="description" name="description" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
                </div>
                <div class="w-full mt-3">
                    <label class="text-sm">{{ __('common.is-active') }} <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" required style="width:100%" class="mt-2 form-input">
                        <option value="" selected disabled>{{ __('common.chooseoption') }}</option>
                        <option value="1">{{ __('common.active') }}</option>
                        <option value="0">{{ __('common.inactive') }}</option>
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
            {{ __('common.edit-tax-type') }}
        </h5>
        <button type="button" onclick="toggle_model('view-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="edit_modal">
        @csrf
        <input type="hidden" id="old_id" name="id" required readonly class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            <div class="p-3 max-h-[85vh] overflow-y-auto">
                <div class="w-full">
                    <label class="text-sm">{{__('common.name')}}<span class="text-danger">*</span></label>
                     <input type="text" id="edit_tax_type_name" name="tax_type_name" required readonly class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label class="text-sm">{{__('common.code')}}<span class="text-danger">*</span></label>
                     <input type="text" id="edit_tax_type_code" name="tax_type_code" required readonly class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
                </div>

                <div class="w-full">
                    <label class="text-sm">{{__('common.description')}}</label>
                    <textarea id="edit_description" name="description" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
                </div>
                <div class="w-full mt-3">
                    <label class="text-sm">{{ __('common.is-active') }} <span class="text-danger">*</span></label>
                    <select name="is_active" id="edit_is_active" required style="width:100%" class="mt-2 form-input">

                    </select>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <button type="button" onclick="toggle_model('add-sidebar')" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i> {{ __('common.close') }}</button>
                <button type="submit" class="btn-success-sm close-drawer-btn save-btn"><i class="ri-save-line"></i> {{ __('common.add') }}</button>
            </div>
        </form>
</div>
