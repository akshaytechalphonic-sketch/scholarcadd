@if ($count < 1 || $step_data->condition == 'Employee' || $step_data->condition == 'Conditional')

<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="add-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.create-assignment') }}
        </h5>
        <button type="button" onclick="toggle_model('add-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
     
    <form id="add_modal">
        @csrf
        <div class="p-3 max-h-[85vh] overflow-y-auto">
            <input type="hidden" name="step_id" id="step_id" value="{{ $step_id }}">
            <input type="hidden" name="setup_id" id="setup_id" value="{{ $setup_id }}">

            <div class="w-full">
                <label class="text-sm">{{ __('company.step-name') }}<span class="text-danger">*</span></label>
                <input type="text" value="{{ $step_data?->step_name }}" readonly
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>
            <div class="w-full">
                <label class="text-sm">{{ __('company.condition') }}<span class="text-danger">*</span></label>
                <input type="text" value="{{ $step_data?->condition }}" readonly
                    class="input-box form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
            </div>

            @if ($step_data?->condition == 'Employee')
                <div class="w-full">
                    <label class="text-sm">{{ __('company.select-user') }}<span class="text-danger">*</span></label>
                    <select name='condition_user' id="condition_user" required style="width:100%"
                        class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user?->id }}">{{ $user?->name }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif($step_data?->condition == 'Other Department')
                <div class="w-full">
                    <label class="text-sm">{{ __('company.select-department') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_dept' id="condition_dept" required style="width:100%"
                        class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department?->id }}">{{ $department?->name }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($step_data?->condition == 'Line Manager')
                <div class="w-full">
                    <label class="text-sm">{{ __('company.line-manager') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_LM' required style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="1">{{ __('common.level-1') }}</option>
                        <option value="2">{{ __('common.level-2') }}</option>
                        <option value="3">{{ __('common.level-3') }}</option>
                        <option value="4">{{ __('common.level-4') }}</option>
                        <option value="5">{{ __('common.level-5') }}</option>
                        <option value="6">{{ __('common.level-6') }}</option>
                        <option value="7">{{ __('common.level-7') }}</option>
                        <option value="8">{{ __('common.level-8') }}</option>
                        <option value="9">{{ __('common.level-9') }}</option>
                        <option value="10">{{ __('common.level-10') }}</option>
                    </select>
                </div>
            @elseif ($step_data?->condition == 'Conditional')
                <div class="w-full">
                    <label class="text-sm"> {{ __('common.type') }} <span class="text-danger">*</span></label>
                    <select name='condition_change' id="condition_change" onchange="$.condition_change()"
                        style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="Employee">Employee</option>
                        <option value="Department">Department</option>
                        <option value="Line Manager">Line Manager</option>
                    </select>
                </div>

                <div class="w-full" id="emp_cndn" style="display: none;">
                    <label class="text-sm"> {{ __('company.select-user') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_user' id="condition_user" style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user?->id }}">{{ $user?->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full" id="dept_cndn" style="display: none;">
                    <label class="text-sm"> {{ __('company.select-department') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_dept' id="condition_dept" style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department?->id }}">{{ $department?->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full" id="linemanager_cndn" style="display: none;">
                    <label class="text-sm"> {{ __('company.line-manager') }}<span
                            class="text-danger">*</span></label>
                    <select name='condition_LM' style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="1">{{ __('common.level-1') }}</option>
                        <option value="2">{{ __('common.level-2') }}</option>
                        <option value="3">{{ __('common.level-3') }}</option>
                        <option value="4">{{ __('common.level-4') }}</option>
                        <option value="5">{{ __('common.level-5') }}</option>
                        <option value="6">{{ __('common.level-6') }}</option>
                        <option value="7">{{ __('common.level-7') }}</option>
                        <option value="8">{{ __('common.level-8') }}</option>
                        <option value="9">{{ __('common.level-9') }}</option>
                        <option value="10">{{ __('common.level-10') }}</option>
                    </select>
                </div>

            @endif

        </div>
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <button type="button" onclick="toggle_model('add-sidebar')" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i>
                {{ __('common.close') }}</button>
            <button type="submit" class="btn-success-sm close-drawer-btn save-btn"><i class="ri-save-line"></i>
                {{ __('common.add') }}</button>
        </div>
    </form>
</div>       
@endif



 <div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="view-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('company.edit-assignment') }}
        </h5>
        <button type="button" onclick="toggle_model('view-sidebar')" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>    

    <form id="edit_modal">
        @csrf
        <div class="p-4">
            <input type="hidden" name="asgn_id" id="asgn_id" value="">
            <input type="hidden" name="step_id" id="step_id" value="{{ $step_id }}">
            <input type="hidden" name="setup_id" id="setup_id" value="{{ $setup_id }}">

            @if ($step_data?->condition == 'Employee')
                <div class="w-full">
                    <label class="text-sm">{{ __('company.select-user') }}<span class="text-danger">*</span></label>
                    <select name='condition_user' id="condition_user" required style="width:100%"
                        class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user?->id }}">{{ $user?->name }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif($step_data?->condition == 'Other Department')
                <div class="w-full">
                    <label class="text-sm">{{ __('company.select-department') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_dept' id="condition_dept" required style="width:100%"
                        class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department?->id }}">{{ $department?->name }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($step_data?->condition == 'Line Manager')
                <div class="w-full">
                    <label class="text-sm">{{ __('company.line-manager') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_LM' id="condition_LM" required style="width:100%"
                        class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="1">{{ __('common.level-1') }}</option>
                        <option value="2">{{ __('common.level-2') }}</option>
                        <option value="3">{{ __('common.level-3') }}</option>
                        <option value="4">{{ __('common.level-4') }}</option>
                        <option value="5">{{ __('common.level-5') }}</option>
                        <option value="6">{{ __('common.level-6') }}</option>
                        <option value="7">{{ __('common.level-7') }}</option>
                        <option value="8">{{ __('common.level-8') }}</option>
                        <option value="9">{{ __('common.level-9') }}</option>
                        <option value="10">{{ __('common.level-10') }}</option>
                    </select>
                </div>
            @elseif ($step_data?->condition == 'Conditional')
                <div class="w-full" id="edit_emp_cndn" style="display: none;">
                    <label class="text-sm"> {{ __('company.select-user') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_user' id="condition_user" style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user?->id }}">{{ $user?->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full" id="edit_dept_cndn" style="display: none;">
                    <label class="text-sm"> {{ __('company.select-department') }} <span
                            class="text-danger">*</span></label>
                    <select name='condition_dept' id="condition_dept" style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department?->id }}">{{ $department?->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full" id="edit_linemanager_cndn" style="display: none;">
                    <label class="text-sm"> {{ __('company.line-manager') }}<span
                            class="text-danger">*</span></label>
                    <select name='condition_LM' id="condition_LM" style="width:100%" class="form-input mt-2">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="1">{{ __('common.level-1') }}</option>
                        <option value="2">{{ __('common.level-2') }}</option>
                        <option value="3">{{ __('common.level-3') }}</option>
                        <option value="4">{{ __('common.level-4') }}</option>
                        <option value="5">{{ __('common.level-5') }}</option>
                        <option value="6">{{ __('common.level-6') }}</option>
                        <option value="7">{{ __('common.level-7') }}</option>
                        <option value="8">{{ __('common.level-8') }}</option>
                        <option value="9">{{ __('common.level-9') }}</option>
                        <option value="10">{{ __('common.level-10') }}</option>
                    </select>
                </div>

            @endif

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