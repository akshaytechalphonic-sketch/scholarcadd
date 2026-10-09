<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="add-sidebar"
    class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.add-' . $type . '') }}
        </h5>
        <button type="button" onclick="toggle_model('add-sidebar')"
            class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">

        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $parent . '-name') }}</label>
            <select id="master_parent_id" class="form-input mb-2"></select>
        </div>

        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $type . '-name') }}</label>
            <input type="text" id="master_name" class="input-box form-input mb-2">
        </div>
        @if ($type == 'Warehouse')
            <div class="w-full">
                <label class="text-sm">{{ __('common.' . $type . '-code') }}</label>
                <input type="text" id="master_code"
                    class="input-box form-input mb-2">
            </div>
        @endif
        <div class="w-full ">
            <label class="text-sm">{{ __('common.description') }}</label>
            <textarea id="master_description"
                class="input-box form-input mb-2"></textarea>
        </div>

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggle_model('add-sidebar')" class="btn-primary-sm">
                    <i class="ri-close-circle-line"></i>
                    {{ __('common.close') }}
                </button>
                <button type="button" class=" master_add btn-success-sm">
                    <i class="ri-add-circle-line"></i>
                    {{ __('common.add') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="view-sidebar"
    class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.edit-' . $type . '') }}
        </h5>
        <button type="button" onclick="toggle_model('view-sidebar')"
            class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <input type="hidden" class="form-control" id="master_child_id" />

        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $parent . '-name') }}</label>
            <input type="text" disabled id="master_parent_name"
                class="input-box form-input mb-2">
        </div>

        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $type . '-name') }}</label>
            <input type="text" disabled id="master_name_edit"
                class="input-box form-input mb-2">
        </div>

        @if ($type == 'Warehouse')
            <div class="w-full">
                <label class="text-sm">{{ __('common.' . $type . '-code') }}</label>
                <input type="text" disabled id="master_code_edit"
                    class="input-box form-input mb-2">
            </div>
        @endif

        <div class="w-full">
            <label class="text-sm">{{ __('common.status') }}</label>
            <select id="master_status_edit"
                class="input-box form-input mb-2"
                required>
                <option value="Active"> Active </option>
                <option value="Inactive"> Inactive </option>
            </select>
        </div>
        <div class="w-full">
            <label class="text-sm">{{ __('common.description') }}</label>
            <textarea id="master_description_edit"
                class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
        </div>

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggle_model('view-sidebar')" class="btn-primary-sm">
                    <i class="ri-close-circle-line"></i>
                    {{ __('common.close') }}
                </button>
                <button type="button" class="master_update btn-success-sm">
                    <i class="ri-add-circle-line"></i>
                    {{ __('common.update') }}
                </button>
            </div>
        </div>
    </div>
</div>
