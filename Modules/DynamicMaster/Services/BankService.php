<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\BankRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class BankService extends Controller
{
    protected BankRepository $repository;

    public function __construct(BankRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {

                $query = $this->repository->yajraList()
                         ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='created_by',function ($query) use ($request){
                    $query->where($request->custom_filter[0], $request->custom_filter[1]);
                })

                ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='created_by',function ($query) use ($request){
                    $item = $request->custom_filter[1];
                    $query->whereHas('creator', function ($subQuery) use ($item) {
                        $subQuery->where('name', $item);
                    });
                })
                ->when($request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',function ($query) use ($request) {
                        $start_date = $request->custom_filter[1];
                        $end_date = $request->custom_filter[2];
                        $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                    }
                )->orderby('id', 'desc');

            $query->when($request->filter, function ($query) use ($request) {
                $query->where('status', $request->filter);
            });


            return DataTables::of($query)
            ->addColumn('status', function ($row) {
                return status_check($row?->status);
            })
            ->addColumn('city', function ($row) {
                return;
            })
            ->addColumn('created_by', function ($row) {
                return $row?->creator?->name;
            })
            ->addColumn('country', function ($row) {
                return $row?->country?->name;
            })
            ->addColumn('main_account', function ($row) {
                return $row?->mainAccount?->name;
            })
            ->addColumn('currency', function ($row) {
                return $row?->currency?->name;
            })
             ->addColumn('bank_group', function ($row) {
                return $row?->bankGroup?->name;
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $view_url = route('bank-edit',['id'=>$incr]);
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';

                $dropdown.= '<li> <a href="'.$view_url.'" class="block px-4 py-2 hover:bg-gray-100"> <i class=" ri-eye-line"></i> View</a> </li>';

                $dropdown.= '</ul> </div></div>';
                    return $dropdown;
                })
                ->rawColumns(['actions','status'])
            ->make(true);

            }

            return view('dynamicmaster::bank.index');

        } catch (\Exception $e) {
            catch_log($e,"Getting list of master data");
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

    public function create(Request $request)
    {
         try {
            return $this->repository->create();
         }
         catch (\Exception $e) {
            catch_log($e,'Bank Create');
            return back()->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->repository->store($request);

            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Bank Store Error');
                return back()->with('error', $response['message'])->withInput();
            }

            return redirect()->route('bank-list')->with('success',$response['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Bank Store Exception');
            return back()->with('error', __('Something went wrong. Please try again.'));
        }
    }

    public function edit($id)
    {
        try {
            return $this->repository->create($id);
        }
        catch (\Exception $e) {
            catch_log($e,'Bank Edit');
            return back()->with('error',$e->getMessage());
        }
    }






}
