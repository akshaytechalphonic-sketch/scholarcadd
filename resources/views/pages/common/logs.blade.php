 <div class="bg-white p-3 rounded-md border">
     <div class="tab-wrapper flex-1    rounded-md  ">
         <div class="tabs mb-2 ">
             <div class="flex flex-wrap gap-0 w-full border-b border-gray-200">
                  <button type="button" data-tab="6"
                     class="tab-btn flex-1 w-auto text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium ">
                     {{ __('common.Timeline') }}
                 </button>
                  <button type="button" data-tab="7"
                     class="tab-btn flex-1 w-auto text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium ">
                     {{ __('common.Comment') }}
                 </button>
                  <button type="button" data-tab="8"
                     class="tab-btn flex-1 w-auto text-center border-b-2 text-black py-3 text-sm px-4 focus:outline-none font-medium ">
                     {{ __('common.attachments') }}
                 </button>

             </div>

         </div>
         <div class="max-h-[50vh] overflow-y-auto ">
             <div class="tab-content  rounded-sm p-2 ">
                 <div data-content="6" class="tab-pane hidden px-4">
                     <div class="max-w-xl mx-auto ">
                         <div class="relative border-l border-gray-300 activity_tab">

                         </div>
                     </div>
                 </div>
                 <div data-content="7" class="tab-pane hidden">
                     <div class="max-w-2xl mx-auto bg-white p-1   space-y-6">
                         <form id="uploadForm_comment">
                             @csrf
                             <input type="hidden" class="inp-hide col-md-9" id="comment_source_id" value=""
                                 name="source_id" required>
                             <input type="hidden" class="inp-hide col-md-9" id="comment_source" value=""
                                 name="source" required>
                            <input type="hidden" class="inp-hide col-md-9" id="comment_module" value=""
                                 name="module" required>
                             <div class="flex">
                                 <input type="file" name="attachment_file[]" id="fileInput" class="hidden"
                                     accept=".pdf,.jpeg,.jpg,.docx,.xlsx" multiple onchange="handleFileSelect(event)" />
                                 <div class="flex-1">
                                     <div class="relative">
                                         <textarea maxlength="200" id="commentInput" name="comment" rows="3"
                                             class="w-full border border-gray-300 rounded-lg p-3 pr-20 resize-none " placeholder="Write your comment..."></textarea>
                                         <label for="fileInput" title="Add Attachment"
                                             class="cursor-pointer absolute bottom-2 right-[50px] text-black z-50 hover:text-blue-600 mt-2">
                                             <i class="ri-attachment-line text-lg text-gray-400 "></i>
                                         </label>
                                         <button title="Send" type="submit"
                                             class="absolute bottom-2 right-2 flex justify-center items-center bg-blue-400 text-white px-2 py-1 mb-1 rounded-md hover:bg-blue-00">
                                             <i class="ri-send-plane-2-line"></i>
                                         </button>
                                     </div>
                                 </div>
                             </div>
                         </form>
                         <div id="filePreview" class="space-y-2"></div>
                         <hr>
                         <div class="space-y-5 comment_tab">
                         </div>

                     </div>
                 </div>
                 <div data-content="8" class="tab-pane hidden">
                     <div class="bg-white  p-2 max-w-xl mx-auto space-y-3 attachment-div">
                        No Attachments
                     </div>
                 </div>
             </div>
         </div>

     </div>
 </div>
