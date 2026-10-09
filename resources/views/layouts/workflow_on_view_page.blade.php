<div class="text-xs w-full bg-white rounded-md p-4 border mb-3">
    <div>
        <div class="flex items-center gap-2 mb-4">
            <i class="ri-reset-right-line text-lg text-primary"></i>
            <h1 class="section-heading m-0">{{ __('pr.Workflow') }}</h1>
        </div>
    </div>
    <div class="text-xs w-full ">
        <div class="overflow-x-auto max-h-[300px] overflow-y-auto my-1">
                <div class="tab-pane" id="workflow" role="tabpanel" data-simplebar style="max-height: 350px;">
                    <div class="card-body table-responsive workflow_tab">
                    </div>
            </div>
        </div>
    </div>
</div>
@include('workflow::workflowTransitions.Lines.model')