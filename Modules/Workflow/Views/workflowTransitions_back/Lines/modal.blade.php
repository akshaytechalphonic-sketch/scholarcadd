<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white w-80" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.reassignment') }}
        </h5>
        <button type="button" onclick="togglview_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form action="{{ route('workflow.assign') }}" class="needs-validation" method="POST" novalidate>
    @csrf
        <div class="p-4">
            <input type="hidden" name="line_id" id="line_id" value="">
            <div class="w-full">
                <label class="text-sm">{{ __('common.Reassign To') }}</label> 
                <select name='reassign' id="reassign" required style="width:100%" class="select-single mt-2 "></select>
            </div>          
            <div class="mt-[50vh]">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="togglview_modelSidebar()" class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600"> {{ __('common.close') }} </button>
                    <button type="submit" class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800"> {{ __('common.save') }} </button>
                </div>
            </div>
        </div>
    </form>
</div>