<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.view') }}
        </h5>
        <button type="button" onclick="toggle_model('view-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <input type="hidden" class="form-control" id="id" />
        <div class="w-full">
            <label class="text-sm">{{ __('company.name') }}</label>
            <input type="text" disabled id="nameView" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>
        <div class="w-full">
            <label class="text-sm">{{ __('company.email') }}</label>
            <input type="text" disabled id="emailView" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>
        {{-- <div class="w-full">
            <label for="companyView" class="text-sm">{{ __('company.company-name') }}</label>
            <select
                class="select-multi mt-1"
                id="companyView"
                name="company_id[]"
                multiple="multiple"
                data-placeholder="{{ __('company.select-company') }}"
                style="width: 100%;"
                required>
            </select>
        </div> --}}

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggle_model('view-sidebar')" class="btn-primary-sm">
                    <i class="ri-close-circle-line"></i>
                    {{ __('common.close') }}
                </button>

            </div>
        </div>
    </div>
</div>






<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
</div>
