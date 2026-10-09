<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\WorkflowAssignmentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class WorkflowAssignmentService extends Controller
{
    protected WorkflowAssignmentRepository $repository;

    public function __construct(WorkflowAssignmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->yajraList($request)
               ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='condition' && $request->custom_filter[0]!='created_by',function ($query) use ($request){
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
                    return $row?->user?->name;
                })
                ->addColumn('step_name', function ($row) {
                    return $row?->step_condition?->step_name;
                })
                ->addColumn('condition', function ($row) {
                    return $row?->step_condition?->condition;
                })

                ->addColumn('assign_to', function ($row) {
                    if($row->employee_id){
                        return $row?->assign_user?->name;
                    }elseif($row->level){
                        return 'Level '.$row->level;
                    }elseif($row->dept_id){
                        return $row?->assign_dept?->name;
                    }
                    return "";
                })
                ->addColumn('actions', function ($row) {
                    $encr_id = encrypt($row?->id);
                    $encr_id ="'$encr_id'";
                    $model_name = "'view-sidebar'";
                    $view_url = route('allworkflowSteps.list',['id'=>$encr_id]); 
                    $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                        <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                        $dropdown.= '<li> <button onclick="view_model('.$model_name.',' . $encr_id . ')" class="block w-full  py-2 hover:bg-gray-100">  <i class="ri-eye-line text-primary"></i> View </button> </li>'; 
                        $dropdown.= '</ul> </div> </div>';
                    return $dropdown;
                })
                    ->rawColumns(['actions','status'])
                ->make(true);
            }

            return $this->repository->list($request);

        } catch (\Exception $e) {
            catch_log($e,"Getting list of workflow assessment");
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

    public function add(Request $request)
    {
         try {
            if($request->w_condition =="Conditional" || ($request->condition_user || $request->condition_dept || $request->condition_LM));
            else{
                return [201,"please fill all fields !"];
            }
            $add = $this->repository->add($request);
            if($add[0])
            {
                return [200,$add[1]];
            }
            else{
                return [202,$add[1]];
            }
         }
         catch (\Exception $e) {
            catch_log($e,'workflow Assignment create');
            return [202,'message' => $e->getMessage()];
        }
    }

    public function show($id)
    {
         try {
            $query = $this->repository->show($id);
            return response()->json($query);
         }
         catch (\Exception $e) {
            catch_log($e,'workflow assessment view');
            return response()->json('error',$e->getMessage());
        }
    }

    public function edit(Request $request)
    {
         try {
            $query = $this->repository->edit($request);            
            if($query[0])
            {
                return [200, __('Record is updated successfully !')];
            }
              return [201, $query[1]];
         }
         catch (\Exception $e) {
            catch_log($e,'workflow assessment update');
            return [201, $e->getMessage()];
        }
    }


}
