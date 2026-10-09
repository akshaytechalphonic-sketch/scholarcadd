
<div id="common_find_item" class=" hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div class="new-modal-content  relative top-3  w-full max-w-4xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-800">
            <div class="flex items-center justify-between p-2 md:p-3 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class=" font-semibold text-gray-900 dark:text-white">
                    {{ __('common.search-items') }}
                </h3>
                <button onclick="closeNewModal('common_find_item')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-target="common_find_item">
                    <i class="ri-close-line text-lg"></i>
                    <span class="sr-only"></span>
                </button>
            </div>
            <!-- New Modal Body -->
            <form id="ItemSearchForm">
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                        <div class="w-full">
                            <label for="" class="text-sm  font-semibold">{{ __('common.name') }} </label>
                            <input name="name" id="search-name" maxlength="15" class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 "> </input>
                        </div>
                        <div class="w-full">
                            <label for="" class="text-sm font-semibold"> {{ __('common.Category') }}</label>
                            <select name="category_id" id="ItemCategory" class="form-input select-single" style="width:100%"> </select>
                        </div>
                    </div>
                    <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                        <div class="w-full">
                            <label for="" class="text-sm  font-semibold">{{ __('common.SubCategory') }}</label>
                            <select name="subcategory_id" id="ItemSubCategory" class="form-input select-single" style="width:100%"> </select>
                        </div>
                        <div class="w-full">
                            <label for="" class="text-sm font-semibold">{{ __('common.item-type') }}</label>
                            <select name="item-type" id="ItemType" class="form-input select-single" style="width:100%" >
                                <option value="">{{ __('common.chooseoption') }}</option>
                                <option value="Item">Item</option>
                                <option value="Service">Service</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                        <div class="w-full">
                            <label for="" class="text-sm  font-semibold">{{ __('common.ItemGroup') }}</label>
                            <select name="item_group_id" id="ItemGroup" class="form-input select-single" style="width:100%"> </select>
                        </div>              
                    </div>

                    <div class=" mx-auto">
                        <div class="flex justify-end md:items-center md:flex-row flex-col mb-4">
                            <div class="flex gap-2">
                                <button type="submit" class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 "> <i class="ri-search-line"></i> {{__('common.search')}} </button>
                                <button type="button" id="getItemListButton" class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 "> <i class="ri-search-line"></i> {{__('common.get-item')}} </button>
                            </div>
                        </div>

                         <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>                                    
                                    <tr class="bg-primary text-white">
                                        <th class="px-4 py-2 text-left text-sm"> <input type="checkbox" id="select-all"> </th>
                                        <th class="px-4 py-2 text-left text-sm">{{ __('common.item_no') }} </th>
                                        <th class="px-4 py-2 text-left text-sm">{{ __('common.name') }}</th>
                                        <th class="px-4 py-2 text-left text-sm">{{ __('common.description') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="FilteredItemListData"> </tbody>                               
                            </table>
                        </div>                       
                    </div>
                </div>                
            </form> 
        </div>
    </div>
</div>