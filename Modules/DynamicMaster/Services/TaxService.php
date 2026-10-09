<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\TaxRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class TaxService extends Controller
{
    protected TaxRepository $repository;

    public function __construct(TaxRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->yajraList()
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='name', function ($query) use ($request){
                    $query->where('tax_type_name', $request->custom_filter[1]);
                })
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='code', function ($query) use ($request){
                    $query->where('tax_type_code', $request->custom_filter[1]);
                });
            return DataTables::of($query)
            ->addColumn('status', function ($row) {
                return status_check($row?->status);
            })
            ->addColumn('created_by', function ($row) {
                return $row?->creator?->name;
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $incr = "'$incr'";
                $model_name = "'view-sidebar'";
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                    $dropdown.= '<li> <button onclick="view_model('.$model_name.',' . $incr . ')" class="block w-full px-4   py-2 hover:bg-gray-100">  <i class="ri-eye-line text-primary"></i> View </button> </li>';
                    $dropdown.= '</ul> </div> </div>';
                return $dropdown;
                })
                ->rawColumns(['actions','status'])
            ->make(true);

            }

            return view('dynamicmaster::taxtype.index');

        } catch (\Exception $e) {
            catch_log($e,"Tax Type of master data");
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


    public function store(Request $request)
    {
        try {
            $response = $this->repository->store($request);
            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Tax Type Store Error');
                return [202, 'message' => $response['message']];
            }
            return [200, 'message' => $response['message']];
        } catch (\Exception $e) {
            catch_log($e, 'Tax Type Store Exception');
            return [202, 'message' => $e->getMessage()];
        }
    }

    public function edit($id)
    {
         try {
            $data = $this->repository->edit($id);

           return response()->json($data);
         }
         catch (\Exception $e) {
            catch_log($e,'Tax Type View');
            return response()->json('error',$e->getMessage());
        }
    }


    // Tax Functions

     public function tax_all(Request $request)
    {
        try {
            if ($request->ajax()) {

                $query = $this->repository->tax_yajraList()
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='code', function ($query) use ($request){
                    $query->where('tax_code', $request->custom_filter[1]);
                })
                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='tax_type', function ($query) use ($request){
                    $query->whereHas('taxType', function ($subQuery) use ($request) {
                        $subQuery->where('tax_type_name', $request->custom_filter[1]);
                    });
                });


            return DataTables::of($query)
            ->addColumn('tax_type', function ($row) {
                return $row?->taxType?->tax_type_name ?? 'N/A';
            })
            ->addColumn('effective_until', function ($row) {
                return date_formate($row?->effective_until);
            })
            ->addColumn('effective_from', function ($row) {
                return date_formate($row?->effective_from);
            })
            ->addColumn('created_by', function ($row) {
                return $row?->creator?->name;
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $incr = "'$incr'";
                $model_name = "'view-sidebar'";
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                    $dropdown.= '<li> <button onclick="view_model('.$model_name.',' . $incr . ')" class="block w-full px-4  py-2 hover:bg-gray-100">  <i class="ri-eye-line text-primary"></i> View </button> </li>';
                    $dropdown.= '</ul> </div> </div>';
                return $dropdown;
                })
                ->rawColumns(['actions','status'])
            ->make(true);

            }
            $data=$this->repository->tax_create();
            return view('dynamicmaster::tax.index', compact('data'));
        } catch (\Exception $e) {
            catch_log($e,"Tax of master data");
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


    public function tax_store(Request $request)
    {
        try {
            $response = $this->repository->tax_store($request);
            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Tax Store Error');
                return [202, 'message' => $response['message']];
            }
            return [200, 'message' => $response['message']];
        } catch (\Exception $e) {
            catch_log($e, 'Tax Store Exception');
            return [202, 'message' => $e->getMessage()];
        }
    }

    public function tax_edit(Request $request)
    {
         try {
            $data = $this->repository->tax_edit($request);
           return response()->json($data);
         }
         catch (\Exception $e) {
            catch_log($e,'Tax View');
            return response()->json('error',$e->getMessage());
        }
    }






}
