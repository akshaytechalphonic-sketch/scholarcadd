<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\All_WorkFlowRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class All_WorkFlowService extends Controller
{
    protected All_WorkFlowRepository $repository;

    public function __construct(All_WorkFlowRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setup_list(Request $request)
    {
        try {
            if ($request->ajax()) {

                $query = $this->repository->setup_list()
                 ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='apply_on' && $request->custom_filter[0]!='created_by',function ($query) use ($request){
                    $query->where($request->custom_filter[0], $request->custom_filter[1]);
                })

                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='apply_on',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('apply_on', function ($subQuery) use ($item) {
                        $subQuery->where('workflow_name', $item);
                    });
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
                ->addColumn('status', function ($row) {
                    return status_check($row?->status);
                })
                ->addColumn('created_at', function ($row) {
                    return date_time_formate($row?->created_at);
                })
                ->addColumn('created_by', function ($row) {
                    return $row?->user?->name;
                })
                ->addColumn('apply_on', function ($row) {
                    return $row?->apply_on?->workflow_name;
                })

                ->addColumn('actions', function ($row) {
                    $encr_id = encrypt($row?->id);
                    $encr_id ="'$encr_id'";
                    $model_name = "'view-sidebar'";
                    $view_url = route('allworkflowSteps.list',['id'=>$encr_id]);
                    $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                        <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                        $dropdown.= '<li> <button onclick="view_model('.$model_name.',' . $encr_id . ')" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-eye-line text-primary"></i> View </button> </li>';
                        $dropdown.= '<li><a href="'.$view_url.'" class="First_model block px-4 py-2 hover:bg-gray-100"> <i class="ri-arrow-right-fill"></i> View Steps</a> </li>';
                        $dropdown.= '</ul> </div> </div>';
                    return $dropdown;
                })
                ->rawColumns(['actions','status'])
                ->make(true);
            }
            $apply_on = Controller::model('WorkflowMap','Workflow')::get();

            return view('workflow::workflow_master.allworkflow.index',compact('apply_on'));

        } catch (\Exception $e) {
            catch_log($e,"Workflow setup list");
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

    public function show_setup($id)
    {
         try {
            $query = $this->repository->show_setup($id);
            return response()->json($query);
         }
         catch (\Exception $e) {
            catch_log($e,'workflow setup view');
            return response()->json('error',$e->getMessage());
        }
    }

    public function edit_setup(Request $request)
    {
         try {
            $query = $this->repository->edit_setup($request);
            return [200,'message' => __('Record is updated successfully !')];
         }
         catch (\Exception $e) {
            catch_log($e,'workflow setup update');
            return [201,'message' => $e->getMessage()];
        }
    }

    public function add_setup(Request $request)
    {
         try {
            $unique = Controller::unique_two('WorkflowSetup', 'workflow_code', $request->w_code, 'workflow_name', $request->w_name,'Workflow');
            if ($unique) {
                return [202,'message' => __('This Record is already exist.')];
            } else {
                $this->repository->add_setup($request);;
                return [200,'message' => __('Record is created successfully !')];
            }
         }
         catch (\Exception $e) {
            catch_log($e,'workflow setup create');
            return [202,'message' => $e->getMessage()];
        }
    }

    //Steps

    public function step_list(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->step_list($request)
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
                ->addColumn('status', function ($row) {
                    return status_check($row?->status);
                })
                ->addColumn('created_at', function ($row) {
                    return date_time_formate($row?->created_at);
                })
                ->addColumn('created_by', function ($row) {                                        
                    if(Controller::helper('WorkflowHelper','Workflow')::wf_step_validation_check($row->id)[0]){
                        $check = '<i class="ri-checkbox-circle-fill text-green-500"></i>';
                    }                    
                    else{
                        $check = '<i class="ri-close-circle-fill text-red-500"></i>';
                    }
                    return $row?->user?->name.' '.$check;
                })
                ->addColumn('workflowCode_Name', function ($row) {
                    return $row?->workflowCode_Name?->workflow_code.' / '.$row?->workflowCode_Name?->workflow_name;
                })
                ->addColumn('next_step', function ($row) {
                    return $row?->next_step?->step_name;
                })
                ->addColumn('step_name', function ($row) {
                    if($row?->substep_type){ return $row?->step_name.' ( '.$row?->substep_type.' ) '; }
                    else{ return $row?->step_name; }
                })

                ->addColumn('actions', function ($row) {
                    $encr_id = encrypt($row?->id);
                    $encr_id ="'$encr_id'";
                    $model_name = "'view-sidebar'";

                    $view_url = route('allworkflowAssignment.list',['id'=>$encr_id]);
                    $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                        <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';

                        if ($row?->condition != 'Line Department'){
                            $dropdown.= '<li> <a href="'.$view_url.'" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-arrow-right-fill"></i>  View Assignment </a> </li>';
                        }
                        $dropdown.= '<li> <button onclick="view_model('.$model_name.',' . $encr_id . ')" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-eye-line text-primary"></i> View </button> </li>';
                        if($row?->condition == 'Conditional' && !$row?->substep_type){
                            $dropdown.= '<li> <button onclick="togglview_conditional_model('.$encr_id.')" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-contract-left-right-line"></i> Condition </button> </li>';
                        }
                        if(!($row?->condition == 'Conditional' && !$row?->substep_type)){

                            $dropdown.= '<li> <button onclick="togglview_nextmodel(' . $encr_id . ')" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-skip-right-line"></i> Next </button> </li>';

                        }
                        $dropdown.= '</ul> </div> </div>';
                    return $dropdown;
                })
                ->rawColumns(['actions','status','created_by'])
                ->make(true);
            }
            $id = decrypt($request->id);
            $setup_id = encrypt($id);
            $company_id = Auth::user()->company_id;
            $data = self::model('WorkflowSetup','Workflow')::where('company_id', $company_id)->where('id', $id)->first();
            if($data){
                return view('workflow::workflow_master.workflowSteps.index', compact('setup_id'));
            }
            else{
                return redirect()->back()->with(['error' =>'Workflow Setup not found !']);
            }
        } catch (\Exception $e) {
            catch_log($e,"Workflow step list");
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

    public function show_step($id)
    {
         try {
            $query = $this->repository->show_step($id);
            return response()->json($query);
         }
         catch (\Exception $e) {
            catch_log($e,'workflow step view');
            return response()->json('error',$e->getMessage());
        }
    }

    public function edit_step(Request $request)
    {
         try {
            $query = $this->repository->edit_step($request);
            return [200,'message' => __('Record is updated successfully !')];
         }
         catch (\Exception $e) {
            catch_log($e,'workflow step update');
            return [201,'message' => $e->getMessage()];
        }
    }

    public function add_step(Request $request)
    {
         try {
            $data=$this->repository->add_step($request);
            if($data)
            {
                return [200,'message' => __('Record is created successfully !')];
            }
            return [202,'message' => __('Something Went Wrong !')];
         }
         catch (\Exception $e) {
            catch_log($e,'workflow step create');
            return [202,'message' => $e->getMessage()];
        }
    }

    public function save_conditional_steps(Request $request)
    {
         try {
            $step_id = decrypt($request->wf_step_id);
            $data=$this->repository->save_conditional_steps($request);
            if($data['status'])
            {
                return [200,'Record is created successfully !'];
            }
            return [201,'Something Went Wrong !']; 
         }
         catch (\Exception $e) {
            catch_log($e,'Save Conditional Step');
            return [201,'Something Went Wrong !']; 
        }
    }


    public function workflow_steps_get_next($id)
    {
         try {
            return $this->repository->workflow_steps_get_next($id);
         }
         catch (\Exception $e) {            
            catch_log($e,'Workflow Steps Get Next');
            return [[],'']; 
        }
    }

    public function workflow_steps_store_next(Request $request)
    {
        try {
            $data = $this->repository->workflow_steps_store_next($request);
            return [200,'Next step added !']; 
         }
         catch (\Exception $e) {
            catch_log($e,'Workflow Steps store Next');
            return [201,'Something went wrong'];  
        }
    }

    public function wf_setup_validation(Request $request)
    {
        try {
            return $this->repository->wf_setup_validation($request);
         }
         catch (\Exception $e) {
            catch_log($e,'Workflow Steps Validation');
            return [false,$e->getMessage()];
        }
    }


}
