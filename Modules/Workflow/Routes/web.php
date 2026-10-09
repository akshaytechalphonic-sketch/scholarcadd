<?php

use Illuminate\Support\Facades\Route;
use Modules\Workflow\Http\Controllers\{
    WorkFlow_MappingController,
    All_WorkFlowController,
    WorkflowAssignmentController,
    WorkflowTransitionController,
    WorkflowDeligationController,
    WorkflowRequestController,
    WorkflowApplyController,
};
Route::group(['prefix' => 'workflow-req', 'middleware' => ['auth','web']], function () {
    Route::get('/workflow-mapping-list', [WorkFlow_MappingController::class, 'index'])->name('workflow-mapping.list');
    Route::post('/map-workflow-show', [WorkFlow_MappingController::class, 'mapWorkflow'])->name('map-workflow.show');
    Route::get('/workflow-map.details/{id}', [WorkFlow_MappingController::class, 'showMapdata'])->name('workflow-map.details');
    Route::get('/workflow-log-details/{id}', [WorkFlow_MappingController::class, 'showLogdata'])->name('workflow-log.details');
    Route::get('/workflow-members-details/{id}', [WorkFlow_MappingController::class, 'showMembersdata'])->name('workflow-members.details');

    Route::get('/allworkflow-list', [All_WorkFlowController::class, 'setup_list'])->name('allworkflow.list');
    Route::get('/show-workflow-setup/{id}', [All_WorkFlowController::class, 'show_setup'])->name('workflow-setup.show');
    Route::post('/edit-workflow-details', [All_WorkFlowController::class, 'edit_setup'])->name('edit-workflow.details');
    Route::post('/add-workflow-details', [All_WorkFlowController::class, 'add_setup'])->name('add-workflow.details');
    Route::get('/allworkflowSteps-list', [All_WorkFlowController::class, 'step_list'])->name('allworkflowSteps.list');
    Route::post('/add-workflow-step', [All_WorkFlowController::class, 'add_step'])->name('add-workflow.step');
    Route::post('/show-workflow-steps/{id}', [All_WorkFlowController::class, 'show_step'])->name('workflow-steps.show');
    Route::post('/edit-workflow-steps', [All_WorkFlowController::class, 'edit_step'])->name('edit-workflow.steps');
    Route::post('/save-conditional-workflow-steps', [All_WorkFlowController::class, 'save_conditional_steps'])->name('save-conditional-workflow.steps');
    Route::post('/workflow-steps-get-next/{id}', [All_WorkFlowController::class, 'workflow_steps_get_next'])->name('workflow-steps-get-next');
    Route::post('/workflow-steps-store-next', [All_WorkFlowController::class, 'workflow_steps_store_next'])->name('workflow-steps-store-next');
    Route::post('/wf-setup-validation', [All_WorkFlowController::class, 'wf_setup_validation'])->name('wf-setup-validation');
    Route::get('/wf-setup-show-hierarchy', [All_WorkFlowController::class, 'wf_setup_show_hierarchy'])->name('wf-setup-show-hierarchy');

    Route::get('/allworkflowAssignment-list', [WorkflowAssignmentController::class, 'index'])->name('allworkflowAssignment.list');
    Route::post('/add-workflow-assignment', [WorkflowAssignmentController::class, 'add'])->name('add-workflow.assignment');
    Route::get('/show-workflow-assignment/{id}', [WorkflowAssignmentController::class, 'show'])->name('workflow-assignment.show');
    Route::post('/edit-workflow-assignment', [WorkflowAssignmentController::class, 'edit'])->name('edit-workflow.assignment');

    Route::get('/workflow-transitionLine-list', [WorkflowTransitionController::class, 'indexLines'])->name('workflow-transitionLine.list');
    Route::get('/workflow-reassign-details/{id}', [WorkflowTransitionController::class, 'showEmployeeDelegation'])->name('workflow-reassign.details');
    Route::post('/workflow.assign', [WorkflowTransitionController::class, 'updateEmployeeDelegation'])->name('workflow.assign');
    Route::get('/workflow-all-transition-list', [WorkflowTransitionController::class, 'all_transition'])->name('workflow-all-transition-list');     

});

Route::group(['prefix' => 'workflow-req', 'middleware' => ['auth','web']], function () {
    Route::get('/workflow-request-list', [WorkflowRequestController::class, 'index'])->name('workflow-request.list');
    Route::get('/workflow-request-yajra', [WorkflowRequestController::class, 'workflow_request_yajra'])->name('workflow-request-yajra');
    Route::post('/approve-workflow-submit', [WorkflowRequestController::class, 'submitApproveRejModal'])->name('approve-workflow.submit');

});

Route::group(['prefix' => 'workflow-req', 'middleware' => ['auth','web']], function () {
    Route::post('/apply-workflow', [WorkflowApplyController::class, 'apply_workflow'])->name('document-apply-wf');
});


Route::group(['prefix' => 'deligation', 'middleware' => ['auth','web']], function () {
    Route::get('/deligation-workflow-list', [WorkflowDeligationController::class, 'index'])->name('deligation-workflow.list');
    Route::post('/add-delegation', [WorkflowDeligationController::class, 'add'])->name('add.delegation');
    Route::get('/mark-inactive/{id}', [WorkflowDeligationController::class, 'markInactive'])->name('delegations.markInactive');
});

    // Route::get('/workflow-transition-list', [WorkflowTransitionController::class, 'index'])->name('workflow-transition.list');
    // Route::get('/workflow-transition-list-yajra', [WorkflowTransitionController::class, 'index_yajra'])->name('workflow-transition.list.yajra');
    // Route::get('/workflow-transition-export', [WorkflowTransitionController::class, 'exportWorkflowTransitions'])->name('workflow-transition.export');

 // Route::get('/workflow-event-details/{id}', [WorkflowTransitionController::class, 'showeventLogs'])->name('workflow-event.details');

    // Route::get('/all-workflow-on-doc', [WorkflowTransitionController::class, 'doc_workflow_list'])->name('all-workflow-on-doc');
// Route::get('/workflow-rejected-req', [WorkflowRequestController::class, 'rejectWorkflow'])->name('workflow-rejected.req');
    // Route::get('/request-action-details/{id}', [WorkflowRequestController::class, 'showApproveRejModal'])->name('request-action.details');

