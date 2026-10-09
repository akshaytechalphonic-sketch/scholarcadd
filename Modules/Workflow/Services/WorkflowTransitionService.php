<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\WorkflowTransitionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class WorkflowTransitionService extends Controller
{
    protected WorkflowTransitionRepository $repository;

    public function __construct(WorkflowTransitionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all_transition(Request $request)
    { 
        try {
            if ($request->ajax()) {

                $query = $this->repository->yajraList()
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='created_by',function ($query) use ($request){
                    $query->where($request->custom_filter[0], $request->custom_filter[1]);
                })

                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='created_by',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('user', function ($subQuery) use ($item) {
                        $subQuery->where('name', $item);
                    });
                })
                ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',function ($query) use ($request) {
                        $start_date = $request->custom_filter[1];
                        $end_date = $request->custom_filter[2];
                        $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                    }
                );

            return DataTables::of($query) 
            ->addColumn('requester', function ($row) {
                return Controller::helper('WorkflowHelper')::doument_requester_byrequester_id($row?->requester_id); 
            })
            ->addColumn('status', function ($row) {
                return status_check($row?->status);
            })
            ->addColumn('type', function ($row) {
                return $row?->mapName?->workflow_name;
            })
            ->addColumn('date', function ($row) {
                return date_formate($row?->date);
            })
            
            ->addColumn('workflow', function ($row) {
                return $row?->source;
            }) 
            ->addColumn('actions', function ($row) {
                $encr_id = encrypt($row?->id);
                $view_url = route('workflow-transitionLine.list',['id'=>$encr_id]);                                 
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                    $dropdown.= '<li> <a href="'.$view_url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class=" ri-eye-line"></i> Workflow Transition Line</a> </li>';                     
                    $dropdown.= '</ul> </div> </div>';
                return $dropdown;
            })
                ->rawColumns(['actions','status'])
            ->make(true);
            } 
            return view('workflow::workflowTransitions.index');
        } catch (\Exception $e) {
            catch_log($e,"Getting All WF transaction");
            if ($request->ajax()) {
                   return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    } 

    public function indexLines(Request $request)
    {
        try {
            $id = decrypt($request->id);
            $head_data = Controller::model('Workflow_TransitionHeader','Workflow')::with('workflowName', 'mapName')->find($id);            
            $data = self::model('Workflow_TransitionLine','Workflow')::with('requesterName', 'step_name','head_data','emp_name')->where('workflow_transition_header', $head_data?->id)->get();
            $all_event=self::model('WorkflowEventHistory','Workflow')::where('doc_id',$head_data->document_id)->get();
            return view('workflow::workflowTransitions.Lines.index', compact('data', 'head_data','all_event'));            
        } catch (\Exception $e) {
            catch_log($e,"Getting All WF transaction");
            if ($request->ajax()) {
                   return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    } 
    
    public function showEmployeeDelegation(Request $request,$id)
    {
        try {
            if(strlen($id)>=20){
                $id = decrypt($request->id);
            }
            $data = Controller::model('Workflow_TransitionLine','Workflow')::find($id);
            $emp_data = self::helper('CommonHelper')::getactiveemployeeexcept($data->emp_id);            
            return response()->json($emp_data); 
        } catch (\Exception $e) {
            catch_log($e,"Getting All WF transaction");            
            return response()->json( [ 'status' => false, 'error' => $e->getMessage()], 500);
            
        }
    } 
    public function updateEmployeeDelegation(Request $request)
    {
        try {
            $id = $request->line_id;
            if(strlen($id)>=20){
                $id = decrypt($id);
            } 
            if(!$id || !$request->reassign){
                return back()->with('error',"Invalid Payload"); 
            }
            $cmp_and_user = Controller::loginandcompany();
            $wx_data = Controller::model('Workflow_TransitionLine','Workflow')::find($id);
            $from_emp=Controller::model('Employee','DynamicMaster')::find($wx_data->emp_id);
            $from_name=$from_emp?->name;

            $to_emp=Controller::model('Employee','DynamicMaster')::find($request->reassign);
            $to_emp=$to_emp?->name;

            $wx_data->emp_id = $request->reassign;
            $wx_data->save();


            $mod = Controller::model('WorkflowEventHistory','Workflow');
            $event_record = new $mod();
            $event_record->company_id = $cmp_and_user[1];
            $event_record->type = $wx_data?->mapName?->workflow_name;
            $event_record->doc_id = $wx_data->document_id;
            $event_record->statement = '<b>' . $wx_data->ref_code . $wx_data->id . '</b>' . ' Delegated from ' .$from_name. ' to ' . $to_emp;
            $event_record->event_date = date('Y-m-d');
            $event_record->status='Delegated';
            $event_record->created_by = $cmp_and_user[0];
            $event_record->updated_by = $cmp_and_user[0];
            $event_record->save();            

            return back()->with('success',"Deligated");            
        } catch (\Exception $e) {
            catch_log($e,"Reassign");
            return back()->with('error',$e->getMessage()); 
            
        }
    } 
    
    
    
  

}
