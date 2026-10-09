<div id="upload-document-modal"
    class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div
        class="new-modal-content relative top-3  w-full max-w-4xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div
                class="flex items-center justify-between px-4 py-2 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                    {{ __('common.documents') }}
                </h3>
                <button onclick="closeNewModalfile('upload-document-modal')" title="Close Modal"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-target="upload-document-modal">
                    <i class="ri-close-line text-lg rotate-on-hover"></i>
                    <span class="sr-only">{{ __('common.close') }}</span>
                </button>
            </div>
            <div class="p-2 md:p-2 space-y-4 max-h-[80vh] overflow-y-scroll">
                <form id="uploadForm">
                    @csrf
                    <input type="hidden" class="form-control" id="source_id_upload" name="source_id">
                    <input type="hidden" class="form-control" id="source_upload" name="source">
                    <input type="hidden" class="form-control" id="module_upload" name="module_name">
                    <div class="p-4 md:p-2 space-y-4">
                        <div>
                            <label class="form-label mt-2">{{ __('tender.choose-file') }} <span
                                    class="text-red-500">*</span></label>
                            <input type="file" class="form-input" id="uploadFile" name="attachment_fileee[]" multiple
                                required>
                        </div>
                        <div>
                            <label class="form-label">{{ __('common.description') }}</label>
                            <textarea class="w-full form-input description-validation" id="description_upload" name="description" rows="2"
                                data-maxlength="200" placeholder="Add a description..."></textarea>
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                            <p id="errorMessage" class="text-red-500 mt-2 hidden"></p>
                        </div>
                        <div class="flex justify-end">
                            <div class="flex gap-2 items-center">
                                <button type="reset" class="btn-primary-sm flex items-center gap-1">
                                    <i class="ri-refresh-line text-md"></i>
                                    {{ __('common.reset') }}
                                </button>
                                <button id="uploadBtn" type="submit" class="btn-success-sm flex items-center gap-1">
                                    <i class="ri-upload-cloud-line text-xs"></i>
                                    {{ __('tender.upload') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="p-3 max-sm:max-w-[90vw] overflow-auto">
                    <h2 class="text-lg font-semibold">{{ __('tender.uploaded-documents') }}</h2>
                    <div class="overflow-x-aut w-full">
                        <table class="w-full mt-2 border-collapse document-table min-w-[700px]">
                            <thead>
                                <tr class="bg-primary text-white text-xs">
                                    <th class="custome-tables">{{ __('tender.sr-no') }}</th>
                                    <th class="custome-tables">{{ __('tender.document-name') }}</th>
                                    <th class="custome-tables">{{ __('tender.document-type') }}</th>
                                    <th class="custome-tables">{{ __('common.description') }}</th>
                                    <th class="custome-tables">{{ __('tender.status') }}</th>
                                    <th class="custome-tables">{{ __('tender.created-on') }}</th>
                                    <th class="custome-tables">{{ __('tender.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
