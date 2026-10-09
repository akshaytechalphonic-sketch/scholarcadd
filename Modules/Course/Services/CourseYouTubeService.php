<?php

namespace Modules\Course\Services;

use Modules\Course\Repositories\CourseYouTubeRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;

class CourseYouTubeService extends Controller
{
    protected CourseYouTubeRepository $repository;

    public function __construct(CourseYouTubeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function courseYouTubeList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->CourseYoutubeList();
                $query = $this->repository->CourseYoutubeFilters($query, $request)->orderBy('id', 'desc');
                 if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('video_description', 'like', "%$search%");
                        // $q->orWhere('short_description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('Coursedate', function ($row) {
                        $view_url = route('course-you-tube-view', ['id' => encrypt($row->id)]);
                        return '<a class="text-blue-500 " href="' . $view_url . '">' . $row?->Coursedate?->course_title . '</a>';
                    })
                    ->addColumn('video_description', function ($row) {
                        return Str::limit($row->video_description ?? 'N/A', 40);
                    })

                    ->addColumn('created_by', function ($row) {
                        return $row?->creator->name;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })

                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $view_url = route('course-you-tube-view', ['id' => $incr]);
                        $edit_url = route('course-you-tube-edit', ['id' => $incr]);
                        $delete_url = route('course-you-tube-destroy', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        $dropdown .= '<li>
                                <a href="javascript:void(0);"
                                onclick="deleteRecord(\''.$delete_url.'\')"
                                class="block px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </a>
                            </li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'Coursedate', 'video_description', 'created_by', 'created_at'])
                    ->make(true);
            }
            return view('Course::CourseYouTube.index');
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
    public function courseYouTubeCreate($id, $view = null)
    {
        try {
            $data = $this->repository->courseYouTubeCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }
    public function CourseYoutubeStore(Request $request)
    {
        try {
            $data = $this->repository->CourseYoutubeStore($request);
           // dd($data);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('course-you-tube-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Course Course");
            return back()->with('error', $e->getMessage());
        }
    }

    public function CourseDestroy($id)
    {
        try {
            $data = $this->repository->CourseDestroy($id);
            if (!$data['status']) {
                if (request()->ajax()) {
                    return response()->json(['status' => false, 'error' => $data['message']]);
                }
                return ['status' => false, 'message' => $data['message']];
            }
            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => $data['message']]);
            }
            return ['status' => true, 'message' => $data['message']];
        } catch (\Exception $e) {
            catch_log($e, "Deleting Course Course");
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
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

    public function updateFromExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');

        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Remove header row
        unset($rows[0]);

        foreach ($rows as $row) {
            if (empty($row[0])) {
                continue;
            }
            $bulletPointsModel = Controller::model('CourseHighlights', 'Course');
            $bulletPointsModel::updateOrCreate(
                [
                    'id' => $row[0], // primary key
                ],
                [
                    'course_id' => $row[1],
                    'country_id' => $row[2],
                    'state_id' => $row[3],
                    'course_description' => $row[4],
                    'course_highlights_in_pointers' => $row[5],
                    'updated_by' => Auth::id(),
                ]
            );
        }

        return back()->with('success', 'Excel data updated successfully.');
    }
}
