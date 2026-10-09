
<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.add-' . $mod . '') }}
        </h5>
        <button type="button" onclick="toggleadd_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $mod . '-name') }}</label>
            <input type="text" id="master_name" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>


        @if($mod == 'BidderRequiredDocument' || $mod == 'Ranking')
        <div class="w-full">
            <label class="text-sm">{{ __('common.document-type') }}</label>                
            <select id="document_type" class="select-single" required style="width:100%">
                <option value="">{{__('common.chooseoption')}}</option>
                <option value="Technical">Technical</option>
                <option value="Commercial">Commercial</option>                            
            </select> 
        </div>
                     

        @elseif($mod == 'TenderRequiredDocument' || $mod == 'QuotationRequiredDocument')
        <div class="w-full mt-4">
            <label class="text-sm">{{ __('common.document-upload') }}</label>                 
            <input type="file" name="upload_file" id="upload_file" class="form-input" onchange="validateFile(this,'image_document',10)" required>             
        </div>
        @endif

        <div class="w-full mt-4">
            <label class="text-sm">@if($mod == 'Ranking') {{ __('common.range')}} @else {{ __('common.description') }} @endif</label>
            <textarea id="master_description" cols="30" rows="3" @if($mod == 'Ranking')  class="number form-input" placeholder="50-70" @else  class="textarea form-input" placeholder="{{ __('common.description') }}" @endif onkeypress="return $.alpha_num_space_period();"></textarea> 
        </div>
 
               
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleadd_modelSidebar()" class="btn-primary-sm">
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
<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.edit-' . $mod . '') }}
        </h5>
        <button type="button" onclick="togglview_modelSidebar()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">        
        <input type="hidden" id="master_id_edit" />   
        <div class="w-full">
            <label class="text-sm">{{ __('common.' . $mod . '-name') }}</label>
            <input type="text" disabled id="master_name_edit" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>

        @if($mod == 'BidderRequiredDocument' || $mod == 'Ranking')
        <div class="w-full">
            <label class="text-sm">{{ __('common.document-type') }}</label>
            <select id="document_type_edit" class="select-single" required style="width:100%">
                <option value="">{{__('common.chooseoption')}}</option>
                <option value="Technical">Technical</option>
                <option value="Commercial">Commercial</option>                            
            </select>
        </div>
        @elseif($mod == 'TenderRequiredDocument' || $mod == 'QuotationRequiredDocument')
        <div class="w-full">
            <label class="text-sm">{{ __('common.document-upload') }}</label>
            <input type="file" name="upload_file_edit" id="upload_file_edit" class="form-input" onchange="validateFile(this,'image_document',10)" required> 
        </div>         
        @endif
        <div class="w-full mt-4">
            <label class="text-sm">@if($mod == 'Ranking') {{ __('common.range')}} @else {{ __('common.description') }} @endif</label>
            <textarea id="master_description_edit" cols="30" rows="3" @if($mod == 'Ranking')  class="number form-input" placeholder="50-70" @else  class="textarea form-input" placeholder="{{ __('common.description') }}" @endif onkeypress="return $.alpha_num_space_period();"></textarea> 
        </div>


  

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="togglview_modelSidebar()" class="btn-primary-sm">
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