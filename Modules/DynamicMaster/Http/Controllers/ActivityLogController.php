<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\LogActivityService;
use Modules\DynamicMaster\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected LogActivityService $service;

    public function __construct(LogActivityService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function comment_list(Request $request)
    {
        return $this->service->comment_list($request);
    }

    protected function comment_store(CommentRequest $request)
    {
        return $this->service->comment_store($request);
    }

    public function doc_workflow_list(Request $request)
    {
        $doc_id = decrypt($request->doc_id);
        $doc_type = $request->doc_type;
        $source = $request->source;
        if($doc_type=="PurchaseRequest")
        {
            $doc_type ="Purchase Request";
            $source ="Purchase Request";
        }
        if($doc_type=="PurchaseOrder")
        {
            $doc_type ="Purchase Order";
            $source ="Purchase Order";
        }
        $wf_map_id = Controller::model('WorkflowMap','Workflow')::where('workflow_name',$doc_type)->pluck('id')->first();
        $header_id = Controller::model('Workflow_TransitionHeader','Workflow')::where('workflow_map_id',$wf_map_id)
        ->where('source',$source)
        ->where('document_id',$doc_id)->pluck('id')->toArray();
        return Controller::model('Workflow_TransitionLine','Workflow')::with('requesterName','emp_name')->whereIn('workflow_transition_header', $header_id)->get();
    }

}
