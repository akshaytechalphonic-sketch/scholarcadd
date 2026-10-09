<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="reassign-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 class="inline-flex items-center text-base font-semibold ">
            {{ __('company.reassign') }}
        </h5>
        <button type="button" onclick="toggle_model('reassign-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
         <form action="{{ route('workflow.assign') }}" class="needs-validation" method="POST" novalidate>
            @csrf
            <input type="hidden" name="line_id" id="line_id" value="">
            <div class="w-full">
                <label class="text-sm">Reassign To</label>
                <select name='reassign' id="reassign" required style="width:100%" class="mt-2 form-input"> </select>               
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggle_model('reassign-sidebar')" class="btn-primary-sm">
                        <i class="ri-close-circle-line"></i>
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" class=" master_add btn-success-sm"> 
                        {{ __('company.reassign') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>