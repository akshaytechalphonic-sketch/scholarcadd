<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="approve-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 class="inline-flex items-center text-base font-semibold ">
            {{ __('company.approve') }}
        </h5>
        <button type="button" onclick="toggle_model('approve-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <form id="approve_modal_form">
        @csrf
            <input type="hidden" name="request_id" class="request_id" value="" required>
            <input type="hidden" name="status" value="Approved" required>
            
            <div class="w-full">
                <label class="text-sm">{{ __('company.add-comments') }}</label>
                <textarea name="comment" required cols="30" rows="10" class="comment form-input border my-1 textarea border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggle_model('approve-sidebar')" class="btn-primary-sm">
                        <i class="ri-close-circle-line"></i>
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" class=" master_add btn-success-sm"> 
                        {{ __('common.approve') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="reject-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 class="inline-flex items-center text-base font-semibold ">
            {{ __('company.reject') }}
        </h5>
        <button type="button" onclick="toggle_model('reject-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <form id="reject_modal_form">
            @csrf
            <input type="hidden" name="request_id" class="request_id" value="" required>
            <input type="hidden" name="status" value="Rejected">
            
            <div class="w-full">
                <label class="text-sm">{{ __('company.add-comments') }}</label>
                <textarea name="comment" required cols="30" rows="10" class="comment form-input border my-1 textarea border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggle_model('reject-sidebar')" class="btn-primary-sm">
                        <i class="ri-close-circle-line"></i>
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" class=" master_add btn-success-sm"> 
                        {{ __('common.reject') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="change-request_modal-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 class="inline-flex items-center text-base font-semibold ">
            {{ __('company.change-request') }}
        </h5>
        <button type="button" onclick="toggle_model('change-request_modal-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <form id="change_request_form">
        @csrf
            <input type="hidden" name="request_id" class="request_id" value="" required>
            <input type="hidden" name="status" value="Change Request">
            
            <div class="w-full">
                <label class="text-sm">{{ __('company.add-comments') }}</label>
                <textarea name="comment" required cols="30" rows="10" class="comment form-input border my-1 textarea border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5"></textarea>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggle_model('change-request_modal-sidebar')" class="btn-primary-sm">
                        <i class="ri-close-circle-line"></i>
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" class=" master_add btn-success-sm"> 
                        {{ __('company.change-request') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>