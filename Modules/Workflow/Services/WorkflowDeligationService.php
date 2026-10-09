<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\WorkflowDeligationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class WorkflowDeligationService extends Controller
{
    protected WorkflowDeligationRepository $repository;

    public function __construct(WorkflowDeligationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->yajraList($request)
               ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='status' ,function ($query) use ($request){
                    $query->where($request->custom_filter[0], $request->custom_filter[1]);
                })
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='employee_name',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('user_name', function ($subQuery) use ($item) {
                        $subQuery->where('name', $item);
                    });
                })
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='scope',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    if($item != "All"){
                        $query->whereHas('map_name', function ($subQuery) use ($item) {
                            $subQuery->where('workflow_name', $item);
                        });
                    }
                    else{
                        $query->whereNull('workflow_id');
                    }
                })
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='delegate_to',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('assign_to', function ($subQuery) use ($item) {
                        $subQuery->where('name', $item);
                    });
                });
                // ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',function ($query) use ($request) {
                //         $start_date = $request->custom_filter[1];
                //         $end_date = $request->custom_filter[2];
                //         $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                //     }
                // );

                return DataTables::of($query)
                ->addColumn('status', function ($row) {
                    return status_check($row?->status);
                })
                ->addColumn('end_date', function ($row) {
                    return date_time_formate($row?->end_date);
                })
                ->addColumn('start_date', function ($row) {
                    return date_time_formate($row?->start_date);
                })
                ->addColumn('scope', function ($row) {
                    return $row?->map_name?->workflow_name ?? "All";
                })
                ->addColumn('employee_name', function ($row) {
                    return $row?->user_name?->name;
                })
                ->addColumn('delegate_to', function ($row) {
                    return $row?->assign_to?->name;
                })
                ->addColumn('actions', function ($row) {
                    if ($row->status === 'Active') {
                        $encr_id = encrypt($row->id);
                        $markInactiveUrl = route('delegations.markInactive', ['id' => $encr_id]);
                        return '<div class="dropdown relative inline-block">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]">
                                <ul class="text-sm text-black">
                                    <li >
                                       <a href="' . $markInactiveUrl . '" class="block px-4 py-2 hover:bg-gray-100">
                                       <i class="ri-close-line"></i>
                                            Mark Inactive
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                    }

                    return '<div class="dropdown relative inline-block">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>';
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
             if ($request->employee_name == $request->delegate_to) {
                return [202,'message' => __("Can't delegate to same person")];
            }
            $data = $this->repository->yajraList($request)->get();
            $count = 0;
            if ($data) {
                foreach ($data as $single) {
                    if ($single->emp_from == $request->employee_name && $single->status == 'Active') {
                        if ($single->start_date <= $request->start_date && $single->end_date >= $request->end_date) {
                            if ($single->workflow_id == null || $single->workflow_id == $request->scope) {
                                $count++;
                                break;
                            }
                        }
                    }
                }
            }
            if ($count == 0) {
                $record = $this->repository->add($request);
                if ($record[0]) {
                    return [200, 'message' => __('Record is created successfully !')];
                }
                else{
                    return [202, 'message' => $record[1]];
                }
            } else {
                return [202, 'message' => __("This Record is already exist !")];
            }

         }
         catch (\Exception $e) {
            catch_log($e,'workflow Delegation create');
            return [202,'message' => $e->getMessage()];
        }
    }

    public function markInactive($id)
    {
         try {
            $query = $this->repository->markInactive($id);
            return back()->with('success', __('Record is marked inactive successfully !'));
         }
         catch (\Exception $e) {
            catch_log($e,'workflow deligation Action');
            return back()->with('error',$e->getMessage());
        }
    }

}
