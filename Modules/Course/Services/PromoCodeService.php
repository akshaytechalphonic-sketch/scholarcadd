<?php

namespace Modules\Course\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Modules\Course\Repositories\PromoCodeRepository;

class PromoCodeService extends Controller
{
    protected PromoCodeRepository $repository;

    public function __construct(PromoCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function PromoCodeList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->PromoCodeList();
                $query = $this->repository->PromoCodeFilters($query, $request)->orderBy('id', 'desc');
                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('PromoCode', function ($row) {
                        return  $row?->promocode;
                    })
                    ->addColumn('Text', function ($row) {
                        return $row->text ;
                    })

                    ->addColumn('courseName', function ($row) {
                        return $row?->coursedate?->course_title;
                    })

                     ->addColumn('PromoType', function ($row) {
                        return $row?->promo_type;
                    })

                    ->addColumn('discount', function ($row) {
                        return $row?->discount_percentage;
                    })

                    ->addColumn('status', function ($row) {
                       return status_check(ucfirst($row->status));
                    })


                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('course-you-tube-view', ['id' => $incr]);
                        $edit_url = route('promo-code-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        // $dropdown .= '<li><button onclick="confirmDeleteHeader(\'' . $incr . '\')" class="block w-full text-left px-6 py-2 hover:bg-gray-100 text-red-600">
                                    // <i class="ri-delete-bin-line mr-2"></i>Delete</button></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'courseName', 'PromoCode', 'status' , 'Text','PromoType' , 'discount' , 'currency' ])
                    ->make(true);
            }
            return view('Course::promo-code.index');
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

     public function PromoCodeCreate($id, $view = null)
    {
        try {
            $data = $this->repository->PromoCodeCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }

    public function PromoCodeStore(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0];
        $data['company_id'] = $cmp_and_user[1];

        // dd($request);

        try {

            if (isset($data['Course_id'])) {
                $id = decrypt($data['Course_id']);
            $result = $this->repository->PromoCodeStore($id, $data);
            } else {

                $result = $this->repository->PromoCodeStore(null , $data);
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
