<?php

namespace Modules\DynamicMaster\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Modules\DynamicMaster\Repositories\ConsultationEnquiryRepository;

class ConsultationEnquiryService extends Controller
{
    protected ConsultationEnquiryRepository $repository;

    public function __construct(ConsultationEnquiryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function EnquiriesList(Request $request)
    {
        try {

            if ($request->ajax()) {
                $query = $this->repository->EnquiriesList();
                $query = $this->repository->EnquiriesFilters($query, $request)->orderBy('id', 'desc');
                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('fullName', function ($row) {
                        return  $row?->full_name;
                    })
                    ->addColumn('enquiryType', function ($row) {
                        return $row->enquiry_for;
                    })
                    ->addColumn('userType', function ($row) {
                        return $row->user_type;
                    })
                    ->addColumn('email', function ($row) {
                        return $row->email;
                    })
                    ->addColumn('phone', function ($row) {
                        return $row->phone;
                    })
                    ->addColumn('Created', function ($row) {
                        return $row->created_at;
                    })
                    ->addColumn('isContacted', function ($row) {
                        return $row->is_contacted;
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('enquiries-view', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';

                        // CONDITION: Show button ONLY if NOT contacted
                        $isNotContacted = !($row->is_contacted === 'Contacted' || $row->is_contacted === 'contacted');

                        if ($isNotContacted) {
                            $dropdown .= '<li>
                                <button onclick="if(confirm(\'Mark as Contacted?\')){$.post(\'' . route('enquiries-contact-status') . '\',{_token:\'' . csrf_token() . '\',id:\'' . $incr . '\'},function(data){if(data.status){toastr.success(data.message);$(\'#data-table-list\').DataTable().ajax.reload(null,false);}else{toastr.error(data.message);}}).fail(function(){toastr.error(\'Error!\');});}"
                                        class="w-full text-left px-6 py-2 hover:bg-green-50 text-green-700 flex items-center">
                                    <i class="ri-check-line mr-2"></i>Mark as Contacted
                                </button>
                            </li>';
                        }

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })


                    ->rawColumns([ 'fullName', 'Created' ,'actions', 'isContacted','enquiryType',  'email', 'phone' ,  'userType' , 'message'])
                    ->make(true);
            }
            return view('dynamicmaster::Enquiries.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Schude data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }
        public function Enquiriesview(Request $request)
    {
        try {
        $id = decrypt($request->id);
        $data = Controller::model('ConsultationEnquiry', 'DynamicMaster')::with('CourseCountryName')->find($id);
        return view('dynamicmaster::Enquiries.view', compact('data'));
         } catch (\Exception $e) {
            catch_log($e, "Getting list of Course Course data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function EnquiriesContactStatus(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0];
        $data['company_id'] = $cmp_and_user[1];

        try {
            if (isset($data['id'])) {
                $id = decrypt($data['id']);
                $result = $this->repository->EnquiriesStatusUpdate($id);
            } else {
                $result = ['status' => false, 'message' => 'ID is required'];
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }



}
