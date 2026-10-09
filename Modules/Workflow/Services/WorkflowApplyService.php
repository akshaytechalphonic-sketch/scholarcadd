<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\WorkflowApplyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class WorkflowApplyService extends Controller
{
    protected WorkflowApplyRepository $repository;

    public function __construct(WorkflowApplyRepository $repository)
    {
        $this->repository = $repository;
    }

    

    public function apply_workflow(Request $request)
    {
         try {
            if($request?->document_type && $request?->doc_id){
                $data = $this->repository->apply_workflow($request?->document_type,$request?->doc_id,$request?->source);
                if($data == "Success"){
                    return response()->json([true,"Submitted to Workflow"]);
                }
                return response()->json([false,$data]);
            }
            else{
                return response()->json([false,"Invalid Payload"]);
            }
         }
         catch (\Exception $e) {
            catch_log($e,'Apply Workflow');
            return response()->json([false,$e->getMessage()]); 
        }
    }
 
}
