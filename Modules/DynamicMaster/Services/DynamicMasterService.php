<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\DynamicMasterRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;


class DynamicMasterService extends Controller
{
    protected DynamicMasterRepository $repository;

    public function __construct(DynamicMasterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            if ($request->ajax()) {
                if ($request->ty) {
                    $type = $request->ty;
                    $data = $this->repository->masteryajraList($type)
                        ->when(
                            $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by',
                            function ($query) use ($request) {
                                $query->where($request->custom_filter[0], $request->custom_filter[1]);
                            }
                        )
                        ->when(
                            $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by',
                            function ($query) use ($request) {
                                $item = $request->custom_filter[1];
                                $query->whereHas('creator', function ($subQuery) use ($item) {
                                    $subQuery->where('name', $item);
                                });
                            }
                        )
                        ->when(
                            $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                            function ($query) use ($request) {
                                $start_date = $request->custom_filter[1];
                                $end_date = $request->custom_filter[2];
                                $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }
                        );

                    return DataTables::of($data)
                        ->addColumn('code', function ($row) use ($type) {
                            if (in_array($type, ["Budget", "CostCenter", "BusinessUnit"])) {
                                return $row?->code;
                            } else {
                                return $row?->name;
                            }
                        })
                        ->addColumn('creator_name_or_code', function ($row) use ($type) {
                            if (in_array($type, ["RatingCriteria", "Project", "Checklist"])) {
                                return $row?->code;
                            } else {
                                return $row?->creator?->name;
                            }
                        })
                        ->addColumn('description', fn($row) => substr($row?->description, 0, 50))
                        ->addColumn('creator_name', fn($row) => $row?->creator?->name)
                        ->addColumn('create_on', fn($row) => date_formate($row?->created_at))
                        ->addColumn('status', fn($row) => status_check($row?->status))
                        ->addColumn('actions', function ($row) use ($type) {
                            $encr_id = encrypt($row?->id);
                            $encr_id = "'$encr_id'";
                            $model_name = 'view-sidebar';
                            $sabCat = route('master-child-list', ['ty' => encrypt('SubCategory'), 'parent_id' => encrypt($row->id)]);
                            // $row_create_url = route('report-row-create', ['header_id' => $encr_id]);

                            $assign_emp = '';
                            if ($type == "Department" || $type == "Position") {
                                $emp_id = "'$row?->employee_id'";
                                $assign_emp = '<li>
                                    <button onclick="assign_employee(' . $emp_id . ',' . $encr_id . ')" class="block w-full py-2 px-6 hover:bg-gray-100">
                                        <i class="ri-edit-line text-primary"></i> Assign Employee
                                    </button> </li>';
                            }



                            $dropdown  = '<div class="dropdown relative inline-block">';
                            $dropdown .= '    <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">';
                            $dropdown .= '        <i class="ri-more-2-fill"></i>';
                            $dropdown .= '    </button>';
                            $dropdown .= '    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]">';
                            $dropdown .= '        <ul class="text-sm text-black">';

                            // // Conditionally add "Create Row"
                            // if ($type == "Category") {
                            //     $dropdown .= '<li><a href="' . $sabCat . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-add-line mr-2"></i>View Sub Category</a></li>';
                            // }

                            // Always add "View"
                            $dropdown .= '<li>';
$dropdown .= '<button
    class="block w-full py-2 px-6 hover:bg-gray-100 view-btn"
    data-id="' . e($encr_id) . '"
    data-modal="view-sidebar">
    <i class="ri-eye-line text-primary"></i> View
</button>';
$dropdown .= '</li>';
;

                            // Append employee assignment button (if any)
                            $dropdown .= $assign_emp;

                            $dropdown .= '        </ul>';
                            $dropdown .= '    </div>';
                            $dropdown .= '</div>';


                            return $dropdown;
                        })
                        ->rawColumns(['actions', 'status'])
                        ->make(true);
                }
            }

            $type = decrypt($request->ty);
            return view('dynamicmaster::parent.index', compact('type'));
        } catch (\Exception $e) {
            catch_log($e, "Getting list of master data");
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

    public function child_all(Request $request)
    {
        try {
            if ($request->ajax()) {
                if ($request->ty) {
                    $type = $request->ty;
                    $parent = $this->parent_name_by_child($type);
                    if (!$parent) {
                        return response()->json(
                            [
                                'status' => false,
                                'error' => 'In Valid URL',
                            ]
                        );
                    }

                    $data = $this->repository->masteryajraList_child($parent, $request)
                        ->when(
                            $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] != 'created_at' && $request->custom_filter[0] != 'created_by',
                            function ($query) use ($request) {
                                $query->where($request->custom_filter[0], $request->custom_filter[1]);
                            }
                        )
                        ->when(
                            $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'created_by',
                            function ($query) use ($request) {
                                $item = $request->custom_filter[1];
                                $query->whereHas('creator', function ($subQuery) use ($item) {
                                    $subQuery->where('name', $item);
                                });
                            }
                        )
                        ->when(
                            $request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0] == 'master_parent_id',
                            function ($query) use ($request) {
                                $item = $request->custom_filter[1];
                                $query->whereHas('masterParent', function ($subQuery) use ($item) {
                                    $subQuery->where('name', $item);
                                });
                            }
                        )
                        ->when(
                            $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'created_at',
                            function ($query) use ($request) {
                                $start_date = $request->custom_filter[1];
                                $end_date = $request->custom_filter[2];
                                $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }
                        );
                    // ->when(
                    //     $request->custom_filter && is_array($request->custom_filter) && $request->custom_filter[0] === 'master_parent_id',
                    //     function ($query) use ($request) {
                    //         $item = $request->custom_filter[1];
                    //         dd($item);
                    //         $query->whereHas('masterParent', function ($subQuery) use ($item) {
                    //             $subQuery->where('name', $item);
                    //         });
                    //     }
                    // )
                    // ->when($request->custom_filter && is_array(request('custom_filter')) && $request->custom_filter[0]=='master_parent_id',function ($query) use ($request){
                    //     $item = $request->custom_filter[1];
                    //     $query->whereHas('masterParent', function ($subQuery) use ($item) {
                    //         $subQuery->where('name', $item);
                    //     });
                    // });

                    return DataTables::of($data)
                        ->addColumn('code', function ($row) use ($type) {
                            if (in_array($type, ["Budget", "CostCenter", "BusinessUnit"])) {
                                return $row?->code;
                            } else {
                                return $row?->name;
                            }
                        })
                        ->addColumn('creator_name_or_code', function ($row) use ($type) {
                            if (in_array($type, ["RatingCriteria", "Project", "Checklist"])) {
                                return $row?->code;
                            } else {
                                return $row?->creator?->name;
                            }
                        })
                        ->addColumn('description', fn($row) => substr($row?->description, 0, 50))
                        ->addColumn('creator_name', fn($row) => $row?->creator?->name)
                        ->addColumn('parent', fn($row) => $row?->masterParent?->name)
                        ->addColumn('create_on', fn($row) => date_formate($row?->created_at))
                        ->addColumn('status', fn($row) => status_check($row?->status))
                        ->addColumn('actions', function ($row) {
                            $encr_id = encrypt($row?->id);
                            $encr_id = "'$encr_id'";
                            $model_name = "'view-sidebar'";
                            $dropdown = '<div class="dropdown relative inline-block">
                                <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]">
                                    <ul class="text-sm text-black">
                                        <li>
                                            <button onclick="view_model(' . $model_name . ',' . $encr_id . ')" class="block w-full py-2 px-6 hover:bg-gray-100">
                                                <i class="ri-eye-line text-primary"></i> View
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>';

                            return $dropdown;
                        })
                        ->rawColumns(['actions', 'status'])
                        ->make(true);
                }
            }

            $type = decrypt($request->ty);
            $parent_id = $request->parent_id??null;
            $parent = $this->parent_name_by_child($type);

            if (!$parent) {
                return back()->with('error', 'Invalid URL');
            }
            return view('dynamicmaster::child.index', compact('type', 'parent','parent_id'));
        } catch (\Exception $e) {
            catch_log($e, "Getting list of master data");
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

    public function parent_name_by_child($child)
    {
        switch ($child) {
            case "SubCategory":
                return "Category";
            case "Warehouse":
                return "Site";
            case "SubSegment":
                return "Segment";
            case "Department":
                return "Employee";
            case "Position":
                return "Employee";
            case "GL SubCategory":
                return "GL Category";
            default:
                return null;
        }
    }



    public function get_parent_list(Request $request)
    {
        try {
            $parent = $this->parent_name_by_child($request->type);
            if (!$parent) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => 'In Valid URL',
                    ]
                );
            }

            $data = $this->repository->get_parent_list($parent);

            return response()->json([
                "status" => true,
                "data" => $data
            ]);
        } catch (\Exception $e) {
            dd($e);
            catch_log($e);
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $next_code = null;
            $master_type_id = null;
            $type = $request->masters_type;
            if ($type) {
                $master_type_id =  self::model('MasterType', 'DynamicMaster')::where('name', $type)->pluck('id')->first();
            }
            if ($request?->masters_code) {
                $next_code = $request->masters_code;
            } else {
                if ($type) {
                    if (!$master_type_id) {
                        return response()->json(['status' => false, 'error' => "Invalid Request"]);
                    }

                    $next_code = self::generate_code($type, $master_type_id, 'MasterParent');
                } elseif ($request->master_parent_id) {
                    $next_code = self::generate_code($type, $request->master_parent_id, 'MasterChild');
                } else {
                    return response()->json(['status' => false, 'error' => "Invalid Request"]);
                }
            }
            if (!$next_code) {
                return response()->json(['status' => false, 'error' => "Number Sequence Error"]);
            }

            $data = $this->repository->store($request, $next_code, $master_type_id);
            if ($data) {
                return response()->json([
                    "status" => true,
                    "success" => 'Record Added Successfully'
                ]);
            }
            return response()->json(
                [
                    'status' => false,
                    'error' => 'Record Not Created',
                ]
            );
        } catch (\Exception $e) {
            catch_log($e);
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function generate_code($type, $master_id, $model)
    {
        $code = null;
        if ($model == 'MasterParent') {
            $data = self::model($model, 'DynamicMaster')::where('master_type_id', $master_id)->orderBy('id', 'Desc')->pluck('code')->first();
        } else if ($model == 'MasterChild') {
            $data = self::model($model, 'DynamicMaster')::where('master_parent_id', $master_id)->orderBy('id', 'Desc')->pluck('code')->first();
        }

        $prefix = substr($type, 0, 3) . '-';
        if ($data) {
            $lastSerialNumberWithoutPrefix = (int) substr($data, strlen($prefix));
            $code = $prefix . str_pad($lastSerialNumberWithoutPrefix + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $code = $prefix . str_pad(1, 4, '0', STR_PAD_LEFT);
        }
        return $code;
    }


    public function edit(Request $request)
    {
        try {
            $data = $this->repository->edit($request);
            return response()->json([
                "status" => true,
                "data" => $data
            ]);
        } catch (\Exception $e) {
            catch_log($e);
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $this->repository->update($request);
            return response()->json([
                "status" => true,
                "success" => 'Record Updated Successfully'
            ]);
        } catch (\Exception $e) {
            catch_log($e);
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function get_child(Request $request)
    {
        try {
            $parent_id = $request->parent_id;
            $data = masterChildData($parent_id);
            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            catch_log($e, "Getting list of master data");
            return response()->json(['status' => false, 'error' => $e->getMessage(),], 500);
        }
    }
    public function switch_company(Request $request)
    {
        try {
            $company_id = $request->company_id;
            return $this->repository->switch_company($company_id);
        } catch (\Exception $e) {
            catch_log($e, "Getting list of master data");
            return response()->json(['status' => false, 'error' => $e->getMessage(),], 500);
        }
    }
}
