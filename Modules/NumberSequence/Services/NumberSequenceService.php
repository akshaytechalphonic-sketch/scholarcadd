<?php

namespace Modules\NumberSequence\Services;

use Modules\NumberSequence\Repositories\NumberSequenceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class NumberSequenceService extends Controller
{
    protected NumberSequenceRepository $repository;

    public function __construct(NumberSequenceRepository $repository)
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
            ->addColumn('mapped', function ($row) {
                if($row?->numbersequence_map_id){
                    return "<input type='checkbox' checked readonly disabled>";
                }
                else{
                    return "<input type='checkbox' readonly disabled>";
                }
            })
            ->addColumn('created_by', function ($row) {
                return $row?->creator?->name;
            })
            ->addColumn('actions', function ($row) {
                $encr_id = encrypt($row?->id);
                $view_url = route('numbersequence-delete',['id'=>$encr_id]);
                $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i>  </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                    if(!$row?->numbersequence_map_id){
                        $dropdown.= '<li><a href="'.$view_url.'" class="First_model block px-4 py-2 hover:bg-gray-100"> <i class="ri-delete-bin-line"></i> Delete</a> </li>';
                    }
                    $dropdown.= '</ul> </div> </div>';
                return $dropdown;
            })
                ->rawColumns(['actions','mapped'])
            ->make(true);
            }
            return view('numbersequence::numbersequence.index');

        } catch (\Exception $e) {
            catch_log($e,"Getting list of number sequence data");
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
            return view('numbersequence::numbersequence.add');
         }
         catch (\Exception $e) {
            catch_log($e,'Number Sequence Create');
            return back()->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->repository->store($request);

            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Number Sequence Store Error');
                return back()->with('error', $response['message'])->withInput();
            }
            return redirect()->route('numbersequence-list')->with('success',$response['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Number Sequence Store Exception');
            return back()->with('error', __('Something went wrong. Please try again.'));
        }
    }

    public function index_map(Request $request)
    {
         try {
            $data_map = $this->repository->index_map($request);
            $data=$data_map['data'];
            $no_seqs=$data_map['no_seqs'];
            $map_time_data=$data_map['map_time_data'];
            return view('numbersequence::numbersequence.map.index',compact('data','no_seqs','map_time_data'));
         }
         catch (\Exception $e) {
            catch_log($e,'Number Sequence Index Map');
            return back()->with('error',$e->getMessage());
        }
    }

    public function store_mapping(Request $request)
    {
        try {
            $response = $this->repository->store_mapping($request);


            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Number Sequence Mapping Store Error');
                return back()->with('error', $response['message'])->withInput();
            }
            return back()->with('success',$response['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Number Sequence Mapping Store Exception');
            return back()->with('error', __('Something went wrong. Please try again.'));
        }
    }

      public function destroy(Request $request)
    {
         try {
            $response = $this->repository->destroy($request);
              if (!$response['status']) {
                return back()->with('error', $response['message']);
              }
            return redirect()->route('numbersequence-list')->with('success',$response['message']);
         }
         catch (\Exception $e) {
            catch_log($e,'Number Sequence Index Map');
            return back()->with('error',$e->getMessage());
        }
    }

}
