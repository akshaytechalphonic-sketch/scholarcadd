<?php

namespace Modules\Course\Services;

use Modules\Course\Repositories\CourseScheduleRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CourseScheduleService extends Controller
{
    protected CourseScheduleRepository $repository;

    public function __construct(CourseScheduleRepository $repository)
    {
        $this->repository = $repository;
    }

public function CourseScheduleList(Request $request)
{
    if ($request->ajax()) {
        $query = $this->repository->CourseScheduleList(); // Base query with company_id filter

        // Apply custom filters FIRST
        $query = $this->repository->CourseScheduleFilters($query, $request);

        return DataTables::of($query)
            // Global search - APPLY AFTER custom filters
            ->filter(function ($query) use ($request) {
                if (!empty($request->search['value'])) {
                    $search = trim($request->search['value']);
                    $query->where(function ($q) use ($search) {
                        $q->where('course_schedule_id', 'LIKE', '%' . $search . '%')
                          ->orWhereHas('coursedate', function ($subQ) use ($search) {
                              $subQ->where('course_title', 'LIKE', '%' . $search . '%');
                          });
                    });
                }
            })
            ->addColumn('course_schedule_id', function ($row) {
                $view_url = route('course-schedule-view', ['id' => encrypt($row->id)]);
                return '<a class="text-blue-500" href="' . $view_url . '">' . $row->course_schedule_id . '</a>';
            })
            ->addColumn('course_includes', function ($row) {
                return $row->country?->name ?? 'N/A';
            })
            ->addColumn('ISO2', function ($row) {
                return $row->country?->iso2 ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                $statusText = strip_tags(status_check(ucfirst($row->status)));
                $bgColor = ($row->status === 'Public') ? '#28a745' : '#f6c83d';  // Green vs Yellow
                $textColor = ($row->status === 'Public') ? '#fff' : '#f6f3f3';
                return '<span style="background-color: ' . $bgColor . '; color: ' . $textColor . ';
                        padding: 6px 18px; border-radius:20px; font-size: 1em; font-weight: 600;">
                        ' . $statusText . '</span>';
            })
            ->addColumn('start_date', function ($row) {
                return date_formate($row->class_start_date);
            })
            ->addColumn('end_date', function ($row) {
                return date_formate($row->class_end_date);
            })
            ->addColumn('course_category', function ($row) {
                return $row->Coursedate?->course_title ?? 'N/A'; // Fix case: coursedate
            })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row->id);
                $view_url = route('course-schedule-view', ['id' => $incr]);
                $delete_url = route('course-schedule-destroy', ['id' => $incr]);

                $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                    <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                        <ul class="text-sm text-black">
                            <li>
                                <a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100">
                                    <i class="ri-eye-line text-primary mr-3"></i>View
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"
                                onclick="deleteRecord(\''.$delete_url.'\')"
                                class="block px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>';

                return $dropdown;
            })

            ->rawColumns(['actions', 'discount_amt', 'status' , 'ISO2' ,'course_category', 'course_schedule_id', 'end_date', 'start_date', 'course_includes'])
            ->make(true);
    }

    return view('Course::CourseSchedule.index');
}


    public function CourseScheduleCreate($id, $view = null)
    {
        try {
            $data = $this->repository->CourseScheduleCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }

    // public function CourseScheduleStore(Request $request)
    // {
    //     try {
    //         $data = $this->repository->CourseScheduleStore($request->all());
    //         if (!$data['status']) {
    //             return back()->with('error', $data['message'])->withInput();
    //         }
    //         return redirect()->route('course-schedule-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
    //     } catch (\Exception $e) {
    //         catch_log($e, "Creating Course Course");
    //         return back()->with('error', $e->getMessage());
    //     }

    // }

    public function CourseScheduleStore(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0];
        $data['company_id'] = $cmp_and_user[1];


        try {

            if (isset($data['id'])) {
                $id = decrypt($data['id']);
            $result = $this->repository->CourseScheduleStore($id, $data);
            } else {

                $result = $this->repository->CourseScheduleStore(null , $data);
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

public function CourseScheduleDestroy($id)
{
    try {
        $data = $this->repository->CourseScheduleDestroy($id);

        if (!$data['status']) {
            if (request()->ajax()) {
                return response()->json(['status' => false, 'error' => $data['message']]);
            }
            return ['status' => false, 'message' => $data['message']];
        }

        // 👈 Success - JSON for AJAX, ARRAY for controller
        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => $data['message']]);
        }

        // 👈 Controller expects this ARRAY format
        return ['status' => true, 'message' => $data['message']];

    } catch (\Exception $e) {
        catch_log($e, "Deleting Course Schedule");
        if (request()->ajax()) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

public function CourseScheduleBulkDestroy(Request $request) // Type-hint Request
{
    try {
        // Validate ids[] array from form payload
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|min:1', // Match your payload IDs
        ]);

        $data = $this->repository->CourseScheduleBulkDestroy($request);

        if (!$data['status']) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => $data['message']
                ], 422);
            }
            return ['status' => false, 'message' => $data['message']];
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => $data['message'],
                'deleted' => $data['deleted'] ?? 0
            ], 200);
        }

        return ['status' => true, 'message' => $data['message']];

    } catch (\Illuminate\Validation\ValidationException $e) {
        $errors = $e->errors();
        $message = $errors['ids'][0] ?? 'Invalid input data.';
        if (request()->ajax()) {
            return response()->json(['status' => false, 'message' => $message], 422);
        }
        return ['status' => false, 'message' => $message];
    } catch (\Exception $e) {
        catch_log($e, "Deleting Course Schedule");
        if (request()->ajax()) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
        return ['status' => false, 'message' => $e->getMessage()];
    }
}






    public function get_filtered_Course(Request $request)
    {
        try {
            $data = $this->repository->get_filtered_Course($request);
            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            catch_log($e, "Getting filtered Course Course");
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }



    //what is learn about


    public function whatisLearnAboutList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->whatisLearnAboutList();
                $query = $this->repository->whatisLearnAboutFilters($query, $request)->orderBy('id', 'desc');

                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }
                return DataTables::of($query)

                   ->addColumn('course_date', function ($row) {
                        return  $row?->Coursedate->course_title;
                    })
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description ?? 'N/A', 120);
                    })

                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })
                      ->addColumn('created_by', function ($row) {
                        return $row?->creator->name;
                    })

                    ->addColumn('country', function ($row) {
                        return $row?->country?->name ?? 'N/A';
                    })
                     ->addColumn('state_date', function ($row) {
                        return $row?->state_date?->name ?? 'N/A';
                    })


                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('course-will-learn-view', ['id' => $incr]);
                        $edit_url = route('course-will-learn-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        // $dropdown .= '<li><button onclick="confirmDeleteHeader(\'' . $incr . '\')" class="block w-full text-left px-6 py-2 hover:bg-gray-100 text-red-600">
                        //             <i class="ri-delete-bin-line mr-2"></i>Delete</button></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'course_date','description', 'country', 'state_date', 'created_at','created_by'])
                    ->make(true);
            }
            return view('Course::LearnAbout.index');
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

    public function whatleanAboutCreate($id, $view = null)
    {
        try {
            $data = $this->repository->CourseScheduleCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }

    public function whatleanAboutStore(Request $request)
    {
        try {
            $data = $this->repository->CourseStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('course-schedule-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Course Course");
            return back()->with('error', $e->getMessage());
        }
    }

    public function whatleanAboutDestroy($id)
    {
        try {
            $data = $this->repository->CourseDestroy($id);
            if (!$data['status']) {
                return response()->json(['status' => false, 'error' => $data['message']]);
            }
            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => $data['message']]);
            }
            return response()->json(['status' => true, 'message' => $data['message']]);
        } catch (\Exception $e) {
            catch_log($e, "Deleting Course Course");
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function get_filtered_whatleanAbout(Request $request)
    {
        try {
            $data = $this->repository->get_filtered_Course($request);
            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            catch_log($e, "Getting filtered Course Course");
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }



    //what is learn about
    public function aboutGeographyList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->aboutGeographyList();
                $query = $this->repository->aboutGeographyFilters($query, $request)->orderBy('id', 'desc');

                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)

                   ->addColumn('country', function ($row) {
                        return  $row?->country->name;
                    })
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description ?? 'N/A', 180);
                    })

                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })
                      ->addColumn('created_by', function ($row) {
                        return $row?->creator->name;
                    })
                     ->addColumn('state_date', function ($row) {
                        return $row?->state_date?->name ?? 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('about-geography-view', ['id' => $incr]);
                        $edit_url = route('about-geography-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        // $dropdown .= '<li><button onclick="confirmDeleteHeader(\'' . $incr . '\')" class="block w-full text-left px-6 py-2 hover:bg-gray-100 text-red-600">
                        //             <i class="ri-delete-bin-line mr-2"></i>Delete</button></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'description', 'country', 'state_date', 'created_at','created_by'])
                    ->make(true);
            }
            return view('Course::AboutGeography.index');
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

    public function aboutGeographyCreate($id, $view = null)
    {
        try {
            $data = $this->repository->CourseScheduleCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }



    //Course Read More Certificate Function create by Lal Singh`
    public function CourseReadMoreList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->CourseReadMoreList();
                $query = $this->repository->CourseReadMoreFilters($query, $request)->orderBy('id', 'desc');

                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }
                return DataTables::of($query)

                   ->addColumn('course_date', function ($row) {
                        return $row?->Coursedate->course_title;
                    })
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description ?? 'N/A', 120);
                    })

                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })
                      ->addColumn('created_by', function ($row) {
                        return $row?->creator->name;
                    })

                    ->addColumn('country', function ($row) {
                        return $row?->country?->name ?? 'N/A';
                    })
                     ->addColumn('state_date', function ($row) {
                        return $row?->state_date?->name ?? 'N/A';
                    })


                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);

                        $edit_url = route('course-read-more-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';


                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';


                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'course_date','description', 'country', 'state_date', 'created_at','created_by'])
                    ->make(true);
            }
            return view('Course::Course_readmore.index');
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

    public function CourseReadMoreCreate($id, $view = null)
    {
        try {
            $data = $this->repository->CourseScheduleCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }


}
