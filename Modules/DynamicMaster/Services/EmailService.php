<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\EmailRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class EmailService extends Controller
{ 
    protected EmailRepository $repository;

    public function __construct(EmailRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {
 
                $query = $this->repository->yajraList()
                    ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]!='created_at' && $request->custom_filter[0]!='created_by',function ($query) use ($request){
                        if ($request->custom_filter[0] === 'email_master') {
                            $query->whereHas('email_master', function ($q) use ($request) {
                                $q->where('name', $request->custom_filter[1]);
                            });
                        }
                        else{
                            $query->where($request->custom_filter[0], $request->custom_filter[1]);
                        }
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
                    );

            $query->when($request->filter, function ($query) use ($request) {
                $query->where('status', $request->filter);
            });


                return datatables()->of($query)
                    ->addIndexColumn()
                    ->addColumn('email_master', function ($row) { 
                        return $row?->email_master?->name;
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })
                    ->addColumn('status', function ($row) {
                        return status_check($row?->status);
                    })
                    ->addColumn('action', function ($row) {
                        $id = encrypt($row?->id);
                        $editURL = route('email-template-edit', ['id' => $id]);

                        $dropdown = '<div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">  <i class="ri-more-2-fill"></i></button>
                                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]"> <ul class="text-sm text-black">';
                        $dropdown.= '<li><a href="'.$editURL.'" class="flex gap-1 px-6 py-2"> <i class="ri-pencil-line text-primary"></i> Edit </a></li>';
                        $dropdown.= '</ul> </div> </div>';

                        return $dropdown;
                    })
                    ->rawColumns(['created_by','created_at','status','action'])
                    ->make(true);
            }
            return view('dynamicmaster::emailtemplate.index');

        } catch (\Exception $e) {
            catch_log($e,"Getting list of Email data");
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
            $data = null; 
            $email = $this->repository->create();                                  
            return view('dynamicmaster::emailtemplate.add',compact('email','data'));
         }
         catch (\Exception $e) {
            catch_log($e,'Email Create');
            return back()->with('error',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->repository->store($request);

            if (!$response['status']) {
                catch_log(new \Exception($response['message']), 'Email Template Store Error');
                return back()->with('error', $response['message'])->withInput();
            }
            return redirect()->route('email-template-list')->with('success',$response['message']);  
        } catch (\Exception $e) {
            catch_log($e, 'Email Template Store Exception');
            return back()->with('error', __('Something went wrong. Please try again.'));
        }
    }

    public function edit(Request $request)
    {
         try {  
            $data = $this->repository->edit($request);
            $email = $this->repository->create();
            return view('dynamicmaster::emailtemplate.add',compact('email','data'));                          
         }
         catch (\Exception $e) {
            catch_log($e);
            return back()->with('error',$e->getMessage());
        }
    }

    public function get_all_email_template(Request $request)
    {
         try {  
            return $this->repository->get_all_email_template($request); 
         }
         catch (\Exception $e) {
            catch_log($e);
            return response()->json('error',$e->getMessage());
        }
    }
    
 




}
