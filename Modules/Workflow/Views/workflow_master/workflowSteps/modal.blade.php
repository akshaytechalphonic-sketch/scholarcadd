<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold "> {{ __('company.create-new-step') }} </h5>
        <button type="button" onclick="toggle_model('add-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="add_modal">
        @csrf
        <div class="p-3 max-h-[85vh] overflow-y-auto">
            <input type="hidden" name="wf_id" value="{{ $setup_id }}">
            <div class="w-full">
                <label class="text-sm">{{ __('company.step-name') }}<span class="text-danger">*</span></label>
                <input type="text" id="w_name" name="w_name" required
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>
            <div class="w-full">
                <label class="text-sm">{{ __('company.condition') }}<span class="text-danger">*</span></label>
                <select name='w_condition' id="w_condition" required style="width:100%" class="form-input mt-2"
                    onchange="$.add_approval_type();">
                    <option value="">{{ __('common.chooseoption') }}</option>
                    <option value="Employee">{{ __('common.employee') }}</option>
                    <option value="Line Department">{{ __('common.line-department') }}</option>
                    <option value="Other Department">{{ __('common.other-department') }}</option>
                    <option value="Line Manager">{{ __('common.line-manager') }}</option>
                    <option value="Signatory">{{ __('common.signatory') }}</option>
                    <option value="Conditional">{{ __('common.conditional') }}</option>
                </select>
            </div>

            <div class="w-full" id="show_approvalType"> </div>

            <div class="flex items-center mb-2 mt-2 w-full">
                <input type="checkbox" name="system_notification" type="checkbox" value="Yes" checked
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm">
                <label class="form-check-label ms-2"> {{ __('email.allow-system-notification') }} </label>
            </div>

            <div class="flex items-center mb-2 mt-2 w-full">
                <input name="email_notification" type="checkbox" value="Yes" id="email_check"
                    onclick="$.email_notification(0,'email_check')"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm">
                <label class="form-check-label ms-2">{{ __('email.allow-email-notification') }} </label>
            </div>


            <div class="w-full" id="email_template_html"> </div>

            <div class="w-full">
                <label class="text-sm">{{ __('company.workflow-status') }}<span class="text-danger">*</span></label>
                <select name="status" id="status" required style="width:100%" class="form-input mt-2">
                    <option value="">{{ __('common.chooseoption') }}</option>
                    <option value="Active">{{ __('common.active') }}</option>
                    <option value="Inactive">{{ __('common.inactive') }}</option>
                </select>
            </div>

        </div>
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <button type="button" onclick="toggle_model('add-sidebar')" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i>
                {{ __('common.close') }}</button>
            <button type="submit" class="btn-success-sm close-drawer-btn save-btn"><i class="ri-save-line"></i>
                {{ __('common.add') }}</button>
        </div>
    </form>
</div>

<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold "> {{ __('company.edit-step') }} </h5>
        <button type="button" onclick="toggle_model('view-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="edit_modal">
        @csrf
        <div class="p-4">
            <input type="hidden" name="step_id" id="step_id" value="">
            <input type="hidden" name="setup_id" id="setup_id" value="{{ $setup_id }}">
            <div class="w-full">
                <label class="text-sm">{{ __('company.step-name') }}</label>
                <input type="text" name="step_name" id="step_name" required
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>

            <div class="w-full mt-4">
                <label class="text-sm">{{ __('company.condition') }} </label>
                <input type="text" name='step_condition' id="step_condition" readonly
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>

            <div class="w-full mt-4" id="edit_approvalType"> </div>

            <div class="flex items-center mb-2 mt-2 w-full">
                <input type="checkbox" name="system_notification" id="edit_system_check" value="Yes"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm">
                <label class="form-check-label ms-2"> {{ __('email.allow-system-notification') }} </label>
            </div>

            <div class="flex items-center mb-2 mt-2 w-full">
                <input type="checkbox" name="email_notification" value="Yes" id="edit_email_check"
                    onclick="$.email_notification(0,'edit_email_check')"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm">
                <label class="form-check-label ms-2">{{ __('email.allow-email-notification') }} </label>
            </div>

            <div class="mb-3" id="edit_email_template_html"> </div>

            <div class="w-full mt-4">
                <label class="text-sm">{{ __('company.workflow-status') }} </label>
                <select name='step_status' id="step_status" required style="width:100%" class="form-input mt-2 ">
                </select>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggle_model('view-sidebar')"
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" 
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                        {{ __('common.update') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
    
</div>

<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="view-next" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.next-step') }}
        </h5>
        <button type="button" onclick="toggle_model('view-next')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
     <form id="next_define_modal">
        @csrf
        <div class="p-4">

            <input type="hidden" name="wf_id" value="{{ $setup_id }}">
            <input type="hidden" id="wf_next_step_id" name="wf_next_step_id">

            <div class="w-full mt-4">
                <label class="text-sm">{{ __('company.step-name') }} </label>
                <input type="text" id="next_w_name" readonly
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>

            <div class="w-full mt-4">
                <label class="text-sm">{{ __('common.next') }} </label>
                <select name='next_step_id' id="next_step_id" required style="width:100%"
                    class="form-input mt-2 "> </select>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="togglview_nextmodel()"
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                    <button type="submit"
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                        {{ __('common.update') }}
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

 

<div id="view-conditional" class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div
        class="new-modal-content relative top-3  w-full max-w-4xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div
                class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                    {{ __('common.condition') }}
                </h3>
                <button onclick="closeNewModal('view-conditional')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-target="view-conditional">
                    <i class="ri-close-line text-lg"></i>
                    <span class="sr-only"></span>
                </button>
            </div>
            <!-- Modal body -->
            <form id="condition_model">   
                @csrf
                <div class="md:p-5 space-y-4 max-h-[80vh] overflow-y-scroll"> 
                            <div class="p-2"> 
                                <input type="hidden" id="wf_step_id" name="wf_step_id"> 
                        
                                <div class="overflow-x-auto p-2">
                                    <table class="display table-auto w-full py-1">
                                        <thead class="bg-primary text-white text-xs">
                                            <tr>
                                                <th>{{ __('common.field') }}</th>
                                                <th>{{ __('common.apply-condition') }}</th>
                                                <th>{{ __('common.value') }}</th>
                                                <th> <button type="button" id="add-line" class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600"> <i class="ri-add-line"></i> </button>  </th>
                                            </tr>
                                        </thead>
                                        <tbody id="add_new_line"> 
                                        
                                        </tbody>
                                    </table>
                                </div>                 
                            </div>
                    
                </div> 
                <!-- Modal footer -->
                <div class="flex justify-end items-center p-4 md:p-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeNewModal('view-conditional')"
                            class="px-3 py-2 text-xs font-medium text-center text-white bg-green-600 rounded-sm hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600"
                            data-target="view-conditional">
                            {{ __('common.close') }}
                        </button> 
                        <button type="submit" class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                            {{ __('common.save') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
</div>
</div>


 
{{--  
<div id="view-next"
    class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white w-80"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            
        </h5>
        <button type="button" onclick="togglview_nextmodel()"
            class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <form id="next_define_modal">
        @csrf
        <div class="p-4">

            <input type="hidden" name="wf_id" value="{{ $setup_id }}">
            <input type="hidden" id="wf_next_step_id" name="wf_next_step_id">

            <div class="w-full mt-4">
                <label class="text-sm">{{ __('company.step-name') }} </label>
                <input type="text" id="next_w_name" readonly
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>

            <div class="w-full mt-4">
                <label class="text-sm">{{ __('common.next') }} </label>
                <select name='next_step_id' id="next_step_id" required style="width:100%"
                    class="form-input mt-2 "> </select>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="togglview_nextmodel()"
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                    <button type="submit"
                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                        {{ __('common.update') }}
                    </button>
                </div>
            </div>

        </div>
    </form>
</div> 
  --}}


  

 

<div id="view-hier" class=" hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div
        class="new-modal-content  relative top-3  w-full max-w-7xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-800">
            <div
                class="flex items-center justify-between p-2 md:p-3 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class=" font-semibold text-gray-900 dark:text-white">
                    Hierarchy
                </h3>
                <button onclick="closeNewModal('view-hier')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-target="view-hier">
                    <i class="ri-close-line text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div> 
            <div class="p-4 md:p-5 space-y-4 max-h-[80vh] overflow-y-scroll">
                <div class="tree">
                    <ul>
                        <li>
                            <a href="#" class="head">Subjects that teaches in Taxila Central College,
                                Horana</a>
                            <ul>
                                <li>
                                    <a href="#">A - Level</a>
                                    <ul>
                                        <li class="low-heads">
                                            <a href="#" class="sub-head">Science</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Agriculture</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Bio Tech</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Biology</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Chemistry</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Physics</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">SFT</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#" class="sub-head">Maths</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Chemistry</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Physics</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Combine Mathematics</a>
                                                    </li>
                                                </ul><br><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Higher Mathematics</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#" class="sub-head">Commerce</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Accounting</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Business</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Economics</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Statistics Business</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#" class="sub-head">Art</a>
                                            <ul>
                                                <ul>
                                                    <li>
                                                        <a href="#">Great Grand Child</a>
                                                    </li>
                                                </ul><br><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Great Grand Child</a>
                                                    </li>
                                                </ul><br><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Great Grand Child</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#" class="sub-head">Tech</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Agro Tech</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">GIT</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">ICT</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Engineering Tech</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">O - Level</a>
                                    <ul>
                                        <li class="low-heads">
                                            <a href="#">Main Subject</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Buddhism</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Science</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Mathematics</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Sinhala</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">English</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">History</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#">Basket I</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Geography</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Civic</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Commerce</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#">Basket II</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">Western Music</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Eastern Music</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Dancing</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Drama</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Sin. Lit</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">En. Lit</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Art</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                        <li class="low-heads">
                                            <a href="#">Basket III</a>
                                            <ul class="main-container">
                                                <ul>
                                                    <li>
                                                        <a href="#">ICT</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Tamil</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Health</a>
                                                    </li>
                                                </ul><br>
                                                <ul>
                                                    <li>
                                                        <a href="#">Agriculture</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </div>

            </div> 
            <div
                class="flex justify-end items-center p-4 md:p-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button onclick="closeNewModal('view-hier')"
                    class="px-3 py-2 text-xs font-medium text-center text-white bg-green-600 rounded-sm hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600"
                    data-target="view-hier">
                    Close
                </button>
            </div>
        </div>
    </div>
</div> 