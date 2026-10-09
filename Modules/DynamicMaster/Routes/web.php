<?php

use Illuminate\Support\Facades\Route;
use Modules\DynamicMaster\Http\Controllers\API\PaymentGatewayController;
use Modules\DynamicMaster\Http\Controllers\DynamicMasterController;
use Modules\DynamicMaster\Http\Controllers\CompanyController;
use Modules\DynamicMaster\Http\Controllers\EmployeeController;
use Modules\DynamicMaster\Http\Controllers\EmailController;
use Modules\DynamicMaster\Http\Controllers\PlanController;
use Modules\DynamicMaster\Http\Controllers\PlanMappingController;
use Modules\DynamicMaster\Http\Controllers\StateController;
use Modules\DynamicMaster\Http\Controllers\TaxController;
use Modules\DynamicMaster\Http\Controllers\ActivityLogController;
use Modules\DynamicMaster\Http\Controllers\DocumentController;
use Modules\DynamicMaster\Http\Controllers\BankController;
use Modules\DynamicMaster\Http\Controllers\ContactSubmissionController;
use Modules\DynamicMaster\Http\Controllers\ConsultationEnquiriesController;
use Modules\DynamicMaster\Http\Controllers\ScheduleAppointmentsController;
use Modules\DynamicMaster\Http\Controllers\TeamApplicationController;

Route::prefix('master')->middleware(['web'])->group(function () {

    Route::get('parent-list', [DynamicMasterController::class, 'index'])->name('master-parent-list')->middleware('can:list master');
    Route::post('parent-store', [DynamicMasterController::class, 'store'])->name('master-parent-store')->middleware('can:create master');
    Route::get('parent-edit', [DynamicMasterController::class, 'edit'])->name('master-parent-edit')->middleware('can:create master');
    Route::post('parent-update', [DynamicMasterController::class, 'update'])->name('master-parent-update')->middleware('can:create master');

    //master child
    Route::get('child-list', [DynamicMasterController::class, 'child_index'])->name('master-child-list')->middleware('can:list master');
    Route::get('get-parent-list', [DynamicMasterController::class, 'get_parent_list'])->name('get-parent-list')->middleware('can:list master');

    Route::post('/switch-company', [DynamicMasterController::class, 'switch_company'])->name('switch-company');

    Route::post('/get-child', [DynamicMasterController::class, 'get_child'])->name('get-child');


    // company route
    Route::get('/company-list', [CompanyController::class, 'index'])->name('company-list')->middleware('can:list master');
    Route::get('/company-create', [CompanyController::class, 'create'])->name('company-create')->middleware('can:create master');
    Route::post('/company-store', [CompanyController::class, 'store'])->name('company-store')->middleware('can:create master');
    Route::get('/company-edit', [CompanyController::class, 'edit'])->name('company-edit')->middleware('can:create master');

    // employee
    Route::get('/employee-list', [EmployeeController::class, 'index'])->name('employee-list')->middleware('can:list master');
    Route::get('/employee-create', [EmployeeController::class, 'create'])->name('employee-create')->middleware('can:create master');
    Route::post('/employee-store', [EmployeeController::class, 'store'])->name('employee-store')->middleware('can:create master');
    Route::get('/employee-edit', [EmployeeController::class, 'edit'])->name('employee-edit')->middleware('can:create master');
    Route::post('/employee-assign', [EmployeeController::class, 'assign_employee'])->name('employee-assign')->middleware('can:create master');
    Route::post('/create-user', [EmployeeController::class, 'create_user'])->name('create-user')->middleware('can:create user');

    //secure document route
    Route::get('/secure-documents', [DynamicMasterController::class, 'secure_file'])->name('secure-documents');

    //Tax Type
    Route::get('/tax-type-list', [TaxController::class, 'index'])->name('tax-type-list')->middleware('can:list master');
    Route::post('/tax-type-store', [TaxController::class, 'store'])->name('tax-type-store')->middleware('can:create master');
    Route::get('/tax-type-edit', [TaxController::class, 'edit'])->name('tax-type-edit')->middleware('can:create master');

    // Tax
    Route::get('/tax-list', [TaxController::class, 'tax_index'])->name('tax-list')->middleware('can:list master');
    Route::post('/tax-store', [TaxController::class, 'tax_store'])->name('tax-store')->middleware('can:create master');
    Route::get('/tax-edit', [TaxController::class, 'tax_edit'])->name('tax-edit')->middleware('can:create master');

    // Bank route
    Route::get('/bank-list', [BankController::class, 'index'])->name('bank-list')->middleware('can:list master');
    Route::get('/bank-create', [BankController::class, 'create'])->name('bank-create')->middleware('can:create master');
    Route::post('/bank-store', [BankController::class, 'store'])->name('bank-store')->middleware('can:create master');
    Route::get('/bank-edit', [BankController::class, 'edit'])->name('bank-edit')->middleware('can:create master');

});

Route::prefix('activity-log')->middleware(['web'])->group(function () {
    Route::get('/list', [ActivityLogController::class, 'index'])->name('activity-log-list');
});

//Global Attachement
Route::group(['attachement' => 'docs', 'middleware' => ['auth', 'web']], function () {
    Route::post('attachement', [DocumentController::class, 'attachementUpload'])->name('attachement-upload');
    Route::get('attachement-list', [DocumentController::class, 'attachementList'])->name('attachement-list');
    Route::get('attachement-delete', [DocumentController::class, 'attachementDelete'])->name('attachement-delete');
    Route::get('attachement-submit', [DocumentController::class, 'attachementSubmit'])->name('attachement-submit');
    Route::get('attachement-submitlist', [DocumentController::class, 'attachementSubmitList'])->name('attachement-submitlist');
});

//Global Comment

Route::group(['prefix' => 'comments', 'middleware' => ['web', 'auth']], function () {
    Route::get('list', [ActivityLogController::class, 'comment_list'])->name('comments-list');
    Route::Post('store', [ActivityLogController::class, 'comment_store'])->name('comment-store');
    Route::any('/all-workflow-on-doc', [ActivityLogController::class, 'doc_workflow_list'])->name('all-workflow-on-doc');
});


Route::prefix('email')->middleware(['web'])->group(function () {
    Route::get('/template-list', [EmailController::class, 'index'])->name('email-template-list')->middleware('can:list master');
    Route::get('/template-create', [EmailController::class, 'create'])->name('email-template-create')->middleware('can:create master');
    Route::get('/template-edit', [EmailController::class, 'edit'])->name('email-template-edit')->middleware('can:create master');
    Route::post('template-store', [EmailController::class, 'store'])->name('email-template-store')->middleware('can:create master');
    Route::get('get-all-email-template', [EmailController::class, 'get_all_email_template'])->name('get-all-email-template')->middleware('permission:list master');
});

Route::prefix('sql')->middleware(['web'])->group(function () {
    Route::get('create', [DynamicMasterController::class, 'sql_create'])->name('sql-create')->middleware('can:list master');
    Route::post('store', [DynamicMasterController::class, 'sql_store'])->name('sql-store')->middleware('can:list master');
});

Route::prefix('state')->middleware(['web'])->group(function () {
    Route::get('index', [StateController::class, 'index'])->name('state.index');
    Route::get('states/data', [StateController::class, 'getStateData'])->name('states.data');
    Route::post('update/{state}', [StateController::class, 'update'])->name('state.update');
    Route::post('delete/{id}', [StateController::class, 'destroy'])->name('state.delete');



    Route::get('create', [StateController::class, 'create'])->name('state.create');
    Route::get('view/{id}', [StateController::class, 'viewOrEdit'])->name('state.view');
    Route::get('edit/{id}', [StateController::class, 'viewOrEdit'])->name('state.edit');
    Route::post('store', [StateController::class, 'store'])->name('state.store');
});


Route::prefix('plans')->middleware(['web'])->group(function () {
    Route::get('index', [PlanController::class, 'index'])->name('plan-index');
    Route::get('data', [PlanController::class, 'getData'])->name('plans.data');
    Route::get('create', [PlanController::class, 'create'])->name('plan-create');
    Route::post('store', [PlanController::class, 'store'])->name('plan-store');
    Route::get('view/{id}', [PlanController::class, 'viewOrEdit'])->name('plan.view');
    Route::get('edit/{id}', [PlanController::class, 'viewOrEdit'])->name('plan.edit');
    Route::post('update/{plan}', [PlanController::class, 'update'])->name('plan.update');
    Route::post('delete/{id}', [PlanController::class, 'destroy'])->name('plan.delete');
    Route::post('add-feature', [PlanController::class, 'addIncludeFeature'])->name('add-feature');


});


Route::prefix('plan-mapping')->middleware(['web'])->group(function () {
    Route::get('index', [PlanMappingController::class, 'index'])->name('plan-mapping-index');
    Route::get('data', [PlanMappingController::class, 'getData'])->name('plan-mappings.data');
    Route::get('create', [PlanMappingController::class, 'create'])->name('plan-mapping-create');
    Route::post('store', [PlanMappingController::class, 'store'])->name('plan-mapping-store');
    Route::get('view/{id}', [PlanMappingController::class, 'viewOrEdit'])->name('plan-mapping.view');
    Route::get('edit/{id}', [PlanMappingController::class, 'viewOrEdit'])->name('plan-mapping.edit');
    Route::post('update/{plan}', [PlanMappingController::class, 'update'])->name('plan-mapping.update');
    Route::post('delete/{id}', [PlanMappingController::class, 'destroy'])->name('plan-mapping.delete');
    Route::get('/plan/{planId}/included-features', [PlanMappingController::class, 'getIncludedFeatures']);
    Route::get('/plan/{planId}/included-plan-features', [PlanMappingController::class, 'getIncludedFeaturesForEdit']);






    Route::get('/courses/{id}/features', [PlanMappingController::class, 'getFeatures'])->name('course.features');
});


Route::prefix('contact-submission')->middleware(['web'])->group(function () {
    Route::get('list', [ContactSubmissionController::class, 'SubmissionList'])->name('submission-list');
    Route::get('view', [ContactSubmissionController::class, 'Submissionview'])->name('submission-view');
    Route::post('status', [ContactSubmissionController::class, 'submissionContactStatus'])->name('submission-contact-status');

});

Route::prefix('Enquiries-consultation')->middleware(['web'])->group(function () {
    Route::get('list', [ConsultationEnquiriesController::class, 'EnquiriesList'])->name('enquiries-list');
    Route::get('view', [ConsultationEnquiriesController::class, 'Enquiriesview'])->name('enquiries-view');
    Route::post('status', [ConsultationEnquiriesController::class, 'EnquiriesContactStatus'])->name('enquiries-contact-status');
});

Route::prefix('schedule-appointment')->middleware(['web'])->group(function () {
    Route::get('list', [ScheduleAppointmentsController::class, 'AppointmentsList'])->name('appointment-list');
    Route::get('view', [ScheduleAppointmentsController::class, 'Appointmentview'])->name('appointment-view');
    Route::post('status', [ScheduleAppointmentsController::class, 'AppointmentContactStatus'])->name('appointment-contact-status');
});

Route::prefix('team-application')->middleware(['web'])->group(function () {
    Route::get('list', [TeamApplicationController::class, 'ApplicationList'])->name('application-list');
    Route::get('view', [TeamApplicationController::class, 'Applicationview'])->name('application-view');
});


Route::post('/ccavenue/callback', [PaymentGatewayController::class, 'ccAvenueCallback']);



