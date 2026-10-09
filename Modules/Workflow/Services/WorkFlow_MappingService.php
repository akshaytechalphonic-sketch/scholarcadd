<?php

namespace Modules\Workflow\Services;

use Modules\Workflow\Repositories\WorkFlow_MappingRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class WorkFlow_MappingService extends Controller
{
    protected WorkFlow_MappingRepository $repository;

    public function __construct(WorkFlow_MappingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try { 
            $data_map = $this->repository->yajraList($request);
            $data=$data_map['data'];
            $workflow=$data_map['workflow'];
            $map_time_data=$data_map['map_time_data'];
            return view('workflow::workflow_master.map.index',compact('data','workflow','map_time_data'));
 
        } catch (\Exception $e) {
            dd($e->getMessage());
            catch_log($e,"Getting list of number sequence data");
             
            return back()->with('error', $e->getMessage());
        }
    }

    // public function create(Request $request)
    // {
    //      try {
    //         return view('numbersequence::numbersequence.add');
    //      }
    //      catch (\Exception $e) {
    //         catch_log($e,'Number Sequence Create');
    //         return back()->with('error',$e->getMessage());
    //     }
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         $response = $this->repository->store($request);

    //         if (!$response['status']) {
    //             catch_log(new \Exception($response['message']), 'Number Sequence Store Error');
    //             return back()->with('error', $response['message'])->withInput();
    //         }
    //         return redirect()->route('numbersequence-list')->with('success',$response['message']);
    //     } catch (\Exception $e) {
    //         catch_log($e, 'Number Sequence Store Exception');
    //         return back()->with('error', __('Something went wrong. Please try again.'));
    //     }
    // }

    // public function index_map(Request $request)
    // {
    //      try {
    //         $data_map = $this->repository->index_map($request);
    //         $data=$data_map['data'];
    //         $no_seqs=$data_map['no_seqs'];
    //         $map_time_data=$data_map['map_time_data'];
    //         return view('numbersequence::numbersequence.map.index',compact('data','no_seqs','map_time_data'));
    //      }
    //      catch (\Exception $e) {
    //         catch_log($e,'Number Sequence Index Map');
    //         return back()->with('error',$e->getMessage());
    //     }
    // }

    public function mapWorkflow(Request $request)
    {
        try {
            $response = $this->repository->mapWorkflow($request);
            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Workflow Mapping Store Error');
                return back()->with('error', $response['message'])->withInput();
            }
            return back()->with('success',$response['message']);
        } catch (\Exception $e) {
            catch_log($e, 'Workflow Mapping Store Exception');
            return back()->with('error', __('Something went wrong. Please try again.'));
        }
    }

    // public function destroy(Request $request)
    // {
    //      try {
    //         $response = $this->repository->destroy($request);
    //           if (!$response['status']) {
    //             return back()->with('error', $response['message']);
    //           }
    //         return redirect()->route('numbersequence-list')->with('success',$response['message']);
    //      }
    //      catch (\Exception $e) {
    //         catch_log($e,'Number Sequence Index Map');
    //         return back()->with('error',$e->getMessage());
    //     }
    // }

}
