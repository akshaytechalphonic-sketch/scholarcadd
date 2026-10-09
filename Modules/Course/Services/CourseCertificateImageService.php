<?php

namespace Modules\Course\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Modules\Course\Repositories\CourseCertificateImageRepository;

class CourseCertificateImageService extends Controller
{
    protected CourseCertificateImageRepository $repository;

    public function __construct(CourseCertificateImageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function CourseCertificateImageList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->CourseCertificateImageList();
                $query = $this->repository->CourseCertificateFilters($query, $request)->orderBy('id', 'desc');
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('courseName', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('courseName', function ($row) {
                        return $row?->Coursedate?->course_title;
                    })
                    ->addColumn('image_path', function ($row) {
                        $imagePath = $row->image_path ?? 'N/A';
                        if ($imagePath !== 'N/A') {
                            $imageUrl = asset($imagePath);
                            return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" alt="Image" style="max-width: 100px; max-height: 100px;"></a>';
                        }
                        return $imagePath;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date_formate($row?->created_at);
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        $edit_url = route('course-certificate-image-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                    <li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>
                                </ul>
                            </div>
                        </div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'image_path'])
                    ->make(true);
            }
            return view('Course::CertificateImage.index');
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

    public function CourseCertificateImageCreate($id, $view = null)
    {
        try {
            $data = $this->repository->CourseCertificateImageCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Course Schedule");
            return back()->with('error', $e->getMessage());
        }
    }

    public function CourseCertificateImageStore(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0];
        $data['company_id'] = $cmp_and_user[1];


        try {

            if (isset($data['id'])) {
                $id = decrypt($data['id']);
            $result = $this->repository->CourseCertificateImageStore($id, $data);
            } else {

                $result = $this->repository->CourseCertificateImageStore(null , $data);
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
